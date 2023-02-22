from flask import request
from databaseconnect import *


def manageLang(postData):
    sql = f"SELECT * FROM {getLangTableName(postData['techType'])}"
    records = getAllRecordsFromDB(sql)
    return records


def updateManageLang(postData):
    updateSql = f"UPDATE {getLangTableName(postData['techType'])} SET LANG_NAME = '{postData['LANG_NAME']}' WHERE ID = {postData['ID']}"
    updateRecordInDB(updateSql)
    return updateSql


def getTechTypeData(postData):
    sql = f"SELECT * FROM {getLangTableName(postData['techType'])}"
    records = getAllRecordsFromDB(sql)
    return records


def getDropDownLang(postData):
    sql = f"SELECT ID FROM {getLangTableName(postData['techType'])}"
    records = getAllRecordsFromDB(sql)
    return records


def saveLangData(postData):
    sql = f"INSERT INTO {getLangTableName(postData['techType'])} (LANG_NAME) VALUES ('{postData['LANG_NAME']}');"
    insertRecordInDB(sql)


def getLangTableName(techType):
    if (techType == "fe"):
        return "tech_site_fe_lang"
    elif (techType == "be"):
        return "tech_site_be_lang"
    else:
        return "tech_site_api_lang"
