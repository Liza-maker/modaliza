import pymysql
conn = pymysql.connect(host='localhost', user='root', password='root', db='modaliza')
print("Connexion réussie !")
conn.close()

