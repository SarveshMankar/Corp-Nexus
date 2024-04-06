from flask import Flask, request, jsonify
from flask_cors import CORS

import google.generativeai as palm

palm.configure(api_key='AIzaSyBjOXYi5hVKQd5fFYiv1B4voFT72Kgm7jA')
models = [m for m in palm.list_models() if 'generateText' in m.supported_generation_methods]
model = models[0].name

og_prompt = "Give me a List of technology stack which I can use for this project. Print it in the form of JSON. Include Frontend, Backend, Server Stack, Database, microservices and cloud services if need."


app = Flask(__name__)
CORS(app)


import requests
import json

API_URL = "https://api-inference.huggingface.co/models/mistralai/Mixtral-8x7B-Instruct-v0.1"
headers = {"Authorization": f"Bearer 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"}

def query(payload):
    response = requests.post(API_URL, headers=headers, json=payload)
    return response.json()

@app.route('/api/gentext', methods=['POST'])
def generate_text():
    if request.method == 'POST':
        input_data = request.json
        # print(input_data)
        
        if 'input_text' in input_data:
            completion = palm.generate_text(
                model=model,
                prompt=input_data['input_text']+og_prompt,
                temperature=0,
                # The maximum length of the response
                max_output_tokens=800,)
            json_str = completion.result.split("```json")[1].split("```")[0]
            print(json_str)

            # convert json string into dictionary and get the keys in a list
            lst=[]
            data = json.loads(json_str)
            for key in data.keys():
                for k in data[key].keys():
                    lst.append(k)
            print(lst)

            return lst
        else:
            return jsonify({'error': 'No input_text provided in the request'}), 400
    else:
        return jsonify({'error': 'Only POST requests are allowed for this endpoint'}), 405

if __name__ == '__main__':
    app.run(debug=True)

