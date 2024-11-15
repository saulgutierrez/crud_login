import mysql.connector
from mysql.connector import Error

def create_connection():
    try:
        connection = mysql.connector.connect(
            host='localhost',
            user='root',
            password='',
            database='crud_login'
        )
        if connection.is_connected():
            print("Conexion exitosa a la base de datos")
            return connection
    except Error as e:
        print(f"Error al conectar a la base de datos: {e}")
        return None
    
def close_connection(connection):
    if connection.is_connected():
        connection.close()
        print("Conexion cerrada correctamente")