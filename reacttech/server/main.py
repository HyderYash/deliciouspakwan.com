import os
from flask import Flask, jsonify, send_from_directory, request
from flask_cors import CORS
from databaseconnect import *
from login import *
from manageLang import *
from siteInfo import *

main = Flask(__name__)
CORS(main)

@main.route('/favicon.ico')
def favicon():
    return send_from_directory(os.path.join(main.root_path, 'static'), 'favicon.ico', mimetype='image/vnd.microsoft.icon')


@main.route('/', methods=['GET', 'POST'])
def routeFn_homepage():
    with main.app_context():
        postData = request.get_json()
        cust_func = postData['FN_NAME']
        return jsonify({"records": eval(cust_func)(postData)})    

if __name__ == '__main__':
    main.run(debug=True)