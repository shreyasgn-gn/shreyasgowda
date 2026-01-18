from flask import Flask, render_template, request, redirect, session, flash
from werkzeug.security import generate_password_hash, check_password_hash
from werkzeug.utils import secure_filename
import mysql.connector, os

app = Flask(__name__)
app.secret_key = "secret123"

# ---------- DATABASE ----------
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="shreyasgowda@123",
    database="book_market"
)
cursor = db.cursor(dictionary=True)

# ---------- UPLOAD FOLDER ----------
UPLOAD_FOLDER = "static/uploads"
if not os.path.exists(UPLOAD_FOLDER):
    os.makedirs(UPLOAD_FOLDER)

# ---------- LOGIN ----------
@app.route("/", methods=["GET", "POST"])
def login():
    if request.method == "POST":
        cursor.execute("SELECT * FROM users WHERE email=%s", (request.form["email"],))
        user = cursor.fetchone()
        if user and check_password_hash(user["password"], request.form["password"]):
            session["user_id"] = user["id"]
            session["name"] = user["name"]
            return redirect("/dashboard")
        flash("Invalid credentials", "error")
    return render_template("login.html")

# ---------- REGISTER ----------
@app.route("/register", methods=["GET", "POST"])
def register():
    if request.method == "POST":
        hashed = generate_password_hash(request.form["password"])
        cursor.execute(
            "INSERT INTO users (name,email,password) VALUES (%s,%s,%s)",
            (request.form["name"], request.form["email"], hashed)
        )
        db.commit()
        flash("Registration successful! Please login.", "success")
        return redirect("/")
    return render_template("register.html")

# ---------- DASHBOARD ----------
@app.route("/dashboard")
def dashboard():
    if "user_id" not in session:
        return redirect("/")
    search = request.args.get("search", "")
    cursor.execute("""
        SELECT books.*, users.name AS seller_name
        FROM books
        JOIN users ON books.seller_id = users.id
        WHERE books.title LIKE %s
    """, ("%" + search + "%",))
    books = cursor.fetchall()
    return render_template("dashboard.html", books=books)

# ---------- ADD BOOK ----------
@app.route("/add", methods=["GET", "POST"])
def add_book():
    if "user_id" not in session:
        return redirect("/")

    if request.method == "POST":
        title = request.form["title"]
        author = request.form["author"]
        price = request.form["price"]
        condition = request.form["condition_book"]
        contact = request.form["contact_number"]
        image_file = request.files.get("image")

        filename = None
        if image_file and image_file.filename:
            filename = secure_filename(image_file.filename)
            image_file.save(os.path.join(UPLOAD_FOLDER, filename))

        cursor.execute("""
            INSERT INTO books
            (title, author, price, condition_book, contact_number, seller_id, image)
            VALUES (%s,%s,%s,%s,%s,%s,%s)
        """, (title, author, price, condition, contact, session["user_id"], filename))

        db.commit()
        flash("Book added successfully!", "success")
        return redirect("/dashboard")

    return render_template("add_book.html")

# ---------- EDIT BOOK ----------
@app.route("/edit/<int:book_id>", methods=["GET", "POST"])
def edit_book(book_id):
    if "user_id" not in session:
        return redirect("/")

    cursor.execute("SELECT * FROM books WHERE id=%s", (book_id,))
    book = cursor.fetchone()

    if not book or book["seller_id"] != session["user_id"]:
        flash("Unauthorized access!", "error")
        return redirect("/dashboard")

    if request.method == "POST":
        title = request.form["title"]
        author = request.form["author"]
        price = request.form["price"]
        condition = request.form["condition_book"]
        contact = request.form["contact_number"]
        image_file = request.files.get("image")

        filename = book["image"]
        if image_file and image_file.filename:
            filename = secure_filename(image_file.filename)
            image_file.save(os.path.join(UPLOAD_FOLDER, filename))

        cursor.execute("""
            UPDATE books 
            SET title=%s, author=%s, price=%s, condition_book=%s,
                contact_number=%s, image=%s
            WHERE id=%s
        """, (title, author, price, condition, contact, filename, book_id))

        db.commit()
        flash("Book updated successfully!", "success")
        return redirect("/dashboard")

    return render_template("edit_book.html", book=book)

# ---------- DELETE BOOK ----------
@app.route("/delete/<int:book_id>")
def delete_book(book_id):
    if "user_id" not in session:
        return redirect("/")

    cursor.execute("SELECT * FROM books WHERE id=%s", (book_id,))
    book = cursor.fetchone()

    if not book or book["seller_id"] != session["user_id"]:
        flash("Unauthorized access!", "error")
        return redirect("/dashboard")

    if book["image"]:
        try:
            os.remove(os.path.join(UPLOAD_FOLDER, book["image"]))
        except:
            pass

    cursor.execute("DELETE FROM books WHERE id=%s", (book_id,))
    db.commit()
    flash("Book deleted successfully!", "success")
    return redirect("/dashboard")

# ---------- LOGOUT ----------
@app.route("/logout")
def logout():
    session.clear()
    return redirect("/")

if __name__ == "__main__":
    app.run(debug=True)
