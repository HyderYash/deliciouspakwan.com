from flask import request
from databaseconnect import *
import hashlib
import random
import string


def checkAdminLogin(postData):
    # Hashing and Securing Password
    secureSuffix = "yash@1234"
    readyToHashPass = postData['ADMIN_PASSWORD'] + secureSuffix
    hashedPassword = hashlib.md5(readyToHashPass.encode())
    sql = f"SELECT * FROM tech_admin_users WHERE ADMIN_USERNAME = '{postData['ADMIN_NAME']}' AND ADMIN_PASSWORD = '{hashedPassword.hexdigest()}'"
    rc = numRecordsInDB(sql)
    if rc == 1:
        getRows = getAllRecordsFromDB(sql)
        adminToken = createAdminToken()
        updateSql = f"UPDATE tech_admin_users SET ADMIN_TOKEN = '{adminToken}' WHERE ID = {getRows[0]['ID']}"
        updateRecordInDB(updateSql)
        return {'status': 'Success', 'message': 'Login Successfull & Record Updated', 'adminToken': adminToken, 'adminID': getRows[0]['ID']}
    else:
        return {'status': 'Failed', 'message': 'Login Failed'}


def createAdminToken(length=32):
    letters_and_digits = string.ascii_letters + string.digits
    result_str = ''.join((random.choice(letters_and_digits)
                          for i in range(length)))
    return result_str
