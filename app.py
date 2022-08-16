from flask import Flask, render_template, url_for, request, redirect, g
import sqlite3
from flask_sqlalchemy import SQLAlchemy
from datetime import datetime

app = Flask(__name__)
# app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:////php_getRate/PHP/rate.db'
# app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
# db = SQLAlchemy(app)
DATABASE = 'C:/Users/Олег/PycharmProjects/pythonProject2/php_getRate/PHP/rate.db'

con = sqlite3.connect("test.db")
cur = con.cursor()

cur.execute('''CREATE TABLE stocks 
(date text, trans text, symbol text, qty real, price real) ''')
cur.execute("INSERT INTO stocks VALUES ('2006-01-05', 'BUY', 'RHAT', 100,35/14)")
con.commit()
con.close()

@app.route('/')
@app.route('/home')
def index():
    return render_template("index.html")



if __name__ == "__main__":
    app.run(debug=True)
