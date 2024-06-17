import sys
import mysql.connector
import os
import nltk
nltk.download('vader_lexicon')
nltk.download('punkt')
from nltk.sentiment.vader import SentimentIntensityAnalyzer
from nltk import sentiment
from nltk import word_tokenize
from textblob import TextBlob
from googletrans import Translator

sql_user = os.environ.get("MYSQL_USER")
sql_password = os.environ.get("MYSQL_PASSWORD")
sql_database = os.environ.get("MYSQL_DATABASE")

if sql_user == None or sql_password == None or sql_database == None:
    sys.exit("Faltan variables de entorno para conexion a BBDD")
# Parámetros de conexión
config = {
    'user': sql_user,
    'password': sql_password,
    'host': 'db',  # Puedes cambiarlo según la configuración de tu servidor MySQL
    'port': 3306,
    'database': sql_database,
}

# Conectar a la base de datos
connection = mysql.connector.connect(**config)

try:
    # Crear un cursor
    cursor = connection.cursor()

    resena_id = sys.argv[1]

    # Ejecutar una consulta para seleccionar el campo de texto de un registro en la tabla resenas
    query = 'SELECT texto FROM resena where id='+resena_id
    cursor.execute(query)

    # Obtener los resultados
    result = cursor.fetchone()

    if result:
        media=0
        campo_texto = result[0]
        # Traducir el texto de español a inglés
        translator = Translator()
        res = translator.translate(campo_texto, dest='en').text
        if res is not None:
            blob = TextBlob(res)
            sentences = blob.sentences
            for sentence in sentences:
                print(sentence)
                polaridad = sentence.sentiment.polarity
                media += polaridad
                print('Puntuacion: ',polaridad)
                print()
            resultado = media / len(sentences)
            print("La puntuacion media es de "+str(resultado))
            sys.exit(resultado)
        else:
            print("El campo de texto está vacío o nulo.")
    else:
        print("No se encontraron resultados.")

finally:
    # Cerrar el cursor y la conexión
    cursor.close()
    connection.close()

