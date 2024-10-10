let charts = {};
document.addEventListener("DOMContentLoaded", async () => {
 document.getElementById("predic").hidden = true;
 document.getElementById("encuesta").hidden = true;
});

async function ejecutarNotebook() {
  await axios
    .post("http://127.0.0.1:5000/execute")
    .then((response) => {
      const data = response.data;
      if (data.error) {
        console.error("Error:", data.error);
      } else {
        if (data.result === "ok") {
          toast("Procesando datos", "info");
        }
      }
    })
    .catch((error) => {
      if (error.response) {
        console.error(
          "Error en la respuesta del servidor:",
          error.response.data
        );
      } else if (error.request) {
        console.error("No se recibió respuesta del servidor:", error.request);
      } else {
        console.error("Error en la solicitud:", error.message);
      }
    });
}

async function guardarPredicciones() {
  await axios
    .post("http://127.0.0.1:5000/save_prediction")
    .then((response) => {
      const data = response.data;
      if (data.error) {
        console.error("Error:", data.error);
      } else {
        if(data.result === "ok") {
          toast("Datos procesados exitosamente", "success");
        }
      }
    })
    .catch((error) => {
      if (error.response) {
        console.error(
          "Error en la respuesta del servidor:",
          error.response.data
        );
      } else if (error.request) {
        console.error("No se recibió respuesta del servidor:", error.request);
      } else {
        console.error("Error en la solicitud:", error.message);
      }
    });
}

async function uploadAndImportCSV(type) {
  const fileInput = document.createElement("input");
  fileInput.type = "file";
  fileInput.accept = ".csv";

  fileInput.onchange = async () => {
    const file = fileInput.files[0];
    if (!file) {
      toast("Seleccione un archivo CSV", "warning");
      return;
    }

    Papa.parse(file, {
      header: true,
      dynamicTyping: true,
      complete: async (results) => {
        if (type === "data") {
          const formData = new FormData();
          formData.append("file", file);

          try {
            const resp = await axios.post(`${BASE_URL}uploadCsv`, formData, {
              headers: {
                "Content-Type": "multipart/form-data",
                Authorization: `Bearer ${TOKEN}`,
              },
            });

            const data = resp.data;
            console.log(data);
            if (data.estado === "ok" && data.codigo === 200) {
              toast("Archivo subido exitosamente", "success");
              await ejecutarNotebook();
              await guardarPredicciones();

              const groupedData = await groupDataByQuestionAndGender(
                results.data
              );

              Object.keys(groupedData).forEach((question) => {
                createAllCharts(groupedData);
              });
              document.getElementById("encuesta").hidden = false;
              document.getElementById("selectFolderButton").hidden = false;
            }
          } catch (error) {
            console.error(error);
            const errorMessage = error.response
              ? error.response.data
              : "Error al subir el archivo";
            toast(`Error: ${errorMessage}`, "error");
          }
        }
      },
    });
  };

  fileInput.click();
}

async function groupDataByQuestionAndGender(data) {
  if (!Array.isArray(data)) {
    console.error("Los datos no están en formato de arreglo");
    return {};
  }

  const grouped = {};

  data.forEach((row) => {
    Object.keys(row).forEach((key) => {
      if (key !== "Sex" && key !== "Marca temporal") {
        if (!grouped[key]) {
          grouped[key] = {
            Masculino: {},
            Femenino: {},
          };
        }

        const gender = row.Sex;
        const response = row[key];

        if (!grouped[key][gender][response]) {
          grouped[key][gender][response] = 0;
        }

        grouped[key][gender][response]++;
      }
    });
  });

  return grouped;
}

function createAllCharts(data) {
  const chartContainer = document.getElementById("chartContainer");
  chartContainer.innerHTML = "";

  Object.keys(data).forEach((question) => {
    const questionData = data[question];

    const canvas = document.createElement("canvas");
    canvas.id = `chart-${question}`;
    canvas.width = 400;
    canvas.height = 200;

    chartContainer.appendChild(canvas);

    createBarChart(questionData, question, canvas.id);
  });
}

function createBarChart(data, question, canvasId) {
  const ctx = document.getElementById(canvasId).getContext("2d");

  const labels = Object.keys(data.Masculino);
  const masculinoData = labels.map((label) => data.Masculino[label] || 0);
  const femeninoData = labels.map((label) => data.Femenino[label] || 0);

  if (charts[question]) {
    charts[question].destroy();
  }

  charts[question] = new Chart(ctx, {
    type: "bar",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Masculino",
          data: masculinoData,
          backgroundColor: "rgba(54, 162, 235, 0.5)",
          borderColor: "rgba(54, 162, 235, 1)",
          borderWidth: 1,
        },
        {
          label: "Femenino",
          data: femeninoData,
          backgroundColor: "rgba(255, 99, 132, 0.5)",
          borderColor: "rgba(255, 99, 132, 1)",
          borderWidth: 1,
        },
      ],
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
      plugins: {
        title: {
          display: true,
          text: `Resultados para ${question}`,
        },
      },
    },
  });
}

async function getLastCsv() {
  try {
    const resp = await axios.get(`${BASE_URL}selectLastCsv`);
    const data = resp.data;
    localStorage.setItem("csvPath", data.data.ruta);
    if (data.estado === "ok" && data.codigo === 200) {
      const { ruta } = data.data;
      const csvData = await readCsvFile(ruta);
      displayChart(csvData);
      document.getElementById("predic").hidden = false;
      document.getElementById("downloadButton").hidden = false;
      document.getElementById("selectFolderButton").hidden = true;
    }
  } catch (error) {
    console.error("Error al obtener el último archivo CSV:", error);
  }
}

async function readCsvFile(filePath) {
  const response = await fetch(filePath);
  const csvText = await response.text();
  const rows = csvText.trim().split('\n').map(row => row.split(','));
  const headers = rows.shift().map(header => header.trim()); 
  const cleanedRows = rows.map(row => row.map(value => value.trim().replace(/\r/g, '')));
  return { headers, data: cleanedRows }; 
}

function displayChart({  data }) {
  const actualValues = data.map(row => Number(row[0])); 
  const predictedValues = data.map(row => Number(row[1])); 
  const ctx = document.getElementById('predicciones').getContext('2d'); 
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: Array.from({ length: actualValues.length }, (_, i) => i + 1),
      datasets: [
        {
          label: 'Actual',
          data: actualValues,
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1,
          fill: false,
          tension: 0.1,
        },
        {
          label: 'Previsto',
          data: predictedValues,
          backgroundColor: 'rgba(255, 99, 132, 0.2)',
          borderColor: 'rgba(255, 99, 132, 1)',
          borderWidth: 1,
          fill: false,
          tension: 0.1,
        }
      ]
    },
    options: {
      responsive: true,
      scales: {
        x: {
          title: {
            display: true,
            text: "Muestra",
          },
          type: 'linear', 
        },
        y: {
          title: {
            display: true,
            text: "Valor",
          },
          beginAtZero: true,
        },
      },
      plugins: {
        zoom: {
          pan: {
            enabled: true,
            mode: 'xy',
          },
          zoom: {
            wheel: {
              enabled: true, 
              speed: 0.1, 
            },
            pinch: {
              enabled: true, 
            },
            drag: {
              enabled: true, 
              backgroundColor: 'rgba(0,0,0,0.1)',
            },
            mode: 'xy', 
          }
        }
      }
    }
  });
}

function downloadFile() {
  const csvPath = localStorage.getItem("csvPath");

  let fileName = prompt("Ingrese el nombre para el archivo (sin extensión):", "predicciones");
  if (!fileName) {
    fileName = "predicciones";
  }
  
  axios({
    url: BASE_URL + csvPath,
    method: 'GET',
    responseType: 'blob',
  })
    .then((response) => {
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', `${fileName}.csv`); 
      document.body.appendChild(link);
      link.click();
      link.remove(); 
    })
    .catch((error) => {
      console.error('Error al descargar el archivo:', error);
    });
}

