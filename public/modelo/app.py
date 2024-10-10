import sys
import os
import asyncio
from flask import Flask, jsonify, request
from flask_cors import CORS
import nbformat
from nbconvert.preprocessors import ExecutePreprocessor
import pandas as pd


if sys.platform == "win32":
    from asyncio.windows_utils import Popen  
    asyncio.set_event_loop_policy(asyncio.WindowsSelectorEventLoopPolicy())  

app = Flask(__name__)
CORS(app)  

@app.route('/', methods=['GET'])
def home():
    return "Bienvenido a la API de Jupyter Notebook"

@app.route('/execute', methods=['POST'])
def execute_notebook():
    try:
  
        with open('main.ipynb', encoding='utf-8') as f:
            nb = nbformat.read(f, as_version=4)
        ep = ExecutePreprocessor(timeout=600, kernel_name='python3')
        ep.preprocess(nb, {'metadata': {'path': './'}})

        return jsonify({'result': 'ok'}), 200
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/save_prediction', methods=['POST'])
def save_prediction():
    try:
        with open('predictions.ipynb', encoding='utf-8') as f:
            nb = nbformat.read(f, as_version=4)

        ep = ExecutePreprocessor(timeout=600, kernel_name='python3')
        ep.preprocess(nb, {'metadata': {'path': './'}})

        return jsonify({'result': 'ok'}), 200
    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)

