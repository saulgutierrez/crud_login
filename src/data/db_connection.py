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

# Funcion para obtener los datos de los posteos (titulos y contenidos)
def fetch_posts_data():
    connection = create_connection()
    if connection is None:
        return []

    query = "SELECT titulo_post, contenido_post FROM post"
    cursor = connection.cursor()
    posts_data = []

    try:
        cursor.execute(query)
        result = cursor.fetchall()
        for row in result:
            post = {
                'titulo_post':    row[0],
                'contenido_post':   row[1]
            }
            posts_data.append(post)
    except Error as e:
        print(f"Error al obtener los datos de los posteos: {e}")
    finally:
        cursor.close()
        close_connection(connection)

        return posts_data