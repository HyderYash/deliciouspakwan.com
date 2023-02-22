from flask import jsonify
import mysql.connector
from flask import g


def connect_db():
    return mysql.connector.connect(user='local_user', password='local_pass', database='home_dp')
    #return mysql.connector.connect(user='pinki_user', password='53U3b7,nStu0', database='ythomedp')


def dbCursor():
    db = getattr(g, '_database', None)
    if db is None:
        db = g._database = connect_db()
    return db.cursor()


def getOneRecordFromDB(query):
    cur = dbCursor()
    cur.execute(query)
    rv = cur.fetchall()
    rc = cur.rowcount
    if rc > 0:
        row_headers = [x[0]
                       for x in cur.description]
        json_data = []
        for result in rv:
            json_data.append(dict(zip(row_headers, result)))
        return json_data
    else:
        return []


def getAllRecordsFromDB(query):
    cur = dbCursor()
    cur.execute(query)
    rv = cur.fetchall()
    rc = cur.rowcount
    if rc > 0:
        row_headers = [x[0]
                       for x in cur.description]
        json_data = []
        for result in rv:
            json_data.append(dict(zip(row_headers, result)))
        return json_data
    else:
        return []


def numRecordsInDB(query):
    cur = dbCursor()
    cur.execute(query)
    cur.fetchall()
    return cur.rowcount


def updateRecordInDB(query):
    cur = dbCursor()
    cur.execute(query)
    return jsonify({"status": 'Success', 'message': 'Records Updated'})


def insertRecordInDB(query):
    cur = dbCursor()
    cur.execute(query)
    return jsonify({"status": 'Success', 'message': 'Records Insert'})
