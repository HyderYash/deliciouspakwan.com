from flask import request
from databaseconnect import *
from manageLang import *
from datetime import datetime as d


def addLangText(langType, tablePrefix):
    qry = f"SELECT LANG_NAME FROM {getLangTableName(tablePrefix)} WHERE ID IN ({langType})"
    feRec = getAllRecordsFromDB(qry)
    tmpFe = []
    f = 0
    for fe in feRec:
        tmpFe.append(fe['LANG_NAME'])
        f = f + 1
    tmpFe = ', '.join([str(elem) for elem in tmpFe])
    return tmpFe


def getSiteInfo(postData):
    sql = "SELECT *, 'FE_LANG', 'BE_LANG','API_LANG' FROM tech_site_info_master"
    records = getAllRecordsFromDB(sql)
    c = 0
    for i in records:
        records[c]['FE_LANG'] = addLangText(i['SITE_FE'], "fe")
        records[c]['BE_LANG'] = addLangText(i['SITE_BE'], "be")
        records[c]['API_LANG'] = addLangText(i['SITE_API'], "api")
        c = c + 1
    return records



def getSiteDataByID(postData):
    sql = f"SELECT * FROM tech_site_info_master WHERE ID = {postData['ID']}"
    records = getAllRecordsFromDB(sql)
    return records


def saveSiteInfoDetails(postData):
    date = d.now()
    lastUpdated = date.strftime("%Y-%m-%d %H:%M:%S")
    postData['SITE_FE'] = ', '.join([str(elem)
                                     for elem in postData['SITE_FE']])
    postData['SITE_BE'] = ', '.join([str(elem)
                                     for elem in postData['SITE_BE']])
    postData['SITE_API'] = ', '.join(
        [str(elem) for elem in postData['SITE_API']])
    updateSql = f"UPDATE tech_site_info_master SET SITE_NAME = '{postData['SITE_NAME']}', SITE_URL = '{postData['SITE_URL']}',	SITE_DESC = '{postData['SITE_DESC']}',	SITE_FE = '{postData['SITE_FE']}', SITE_BE = '{postData['SITE_BE']}', SITE_API = '{postData['SITE_API']}', SITE_STATUS = '{postData['SITE_STATUS']}',	SITE_LAST_UPDATED = '{lastUpdated}' WHERE ID = {postData['ID']}"
    updateRecordInDB(updateSql)
    return "Site Info Updated"


def saveSiteData(postData):
    postData['SITE_FE'] = ', '.join([str(elem)
                                     for elem in postData['SITE_FE']])
    postData['SITE_BE'] = ', '.join([str(elem)
                                     for elem in postData['SITE_BE']])
    postData['SITE_API'] = ', '.join(
        [str(elem) for elem in postData['SITE_API']])
    date = d.now()
    lastUpdated = date.strftime("%Y-%m-%d %H:%M:%S")
    sql = f"INSERT INTO tech_site_info_master (SITE_NAME, SITE_URL, SITE_DESC, SITE_FE, SITE_BE, SITE_API, SITE_STATUS, SITE_LAST_UPDATED) VALUES ('{postData['SITE_NAME']}', '{postData['SITE_URL']}', '{postData['SITE_DESC']}', '{postData['SITE_FE']}', '{postData['SITE_BE']}', '{postData['SITE_API']}', '{postData['SITE_STATUS']}', '{lastUpdated}')"
    insertRecordInDB(sql)
    return "Site Info Updated"
    
    


