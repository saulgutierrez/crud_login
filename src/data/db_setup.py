import mysql.connector

def connect_to_db():
    return mysql.connector.connect(
        host='localhost',
        user='root',
        password='',
        database='crud_login'
    )