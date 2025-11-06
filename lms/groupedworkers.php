<!-- groupedworkers.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Add Worker Group</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #dbe6f6, #c5796d);
            display: flex;
            justify-content: center;
            align-items: start;
            padding: 40px 0;
            min-height: 100vh;
            margin: 0;
        }

        form {
            background: rgba(251, 251, 251, 0.1);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 700px;
            animation: fadeIn 1s ease;
        }

        h2, h3 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            animation: slideDown 1s ease;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .member-section {
            border: 1px dashed #aaa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.15);
        }

        input[type="submit"] {
            width: 100%;
            background-color: #6c63ff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #594dd1;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        function showMembers() {
            const count = document.getElementById('member_count').value;
            const container = document.getElementById('members_container');
            container.innerHTML = '';

            for (let i = 1; i <= count; i++) {
                container.innerHTML += `
                    <div class="member-section">
                        <h3>Member ${i}</h3>
                        <label>Name:</label>
                        <input type="text" name="member_name[]" required>
                        <label>Experience (in years):</label>
                        <input type="number" name="member_experience[]" min="0" required>
                        <label>Photo:</label>
                        <input type="file" name="member_photo[]" accept="image/*" required>
                    </div>
                `;
            }
        }
    </script>
</head>
<body>
    <form action="submit_group.php" method="POST" enctype="multipart/form-data">
        <h2>Add Worker Group</h2>

        <label>Group Name:</label>
        <input type="text" name="group_name" required>

        <label>Group Work Type:</label>
<select name="group_work" required>
    <option value="">-- Select Work --</option>
    <option value="Electrician">Electrician</option>
    <option value="Plumber">Plumber</option>
    <option value="Painter">Painter</option>
    <option value="Carpenter">Carpenter</option>
    <option value="Mason">Mason</option>
    <option value="Cleaner">Cleaner</option>
    <option value="Labour">Labour</option>
</select>

        <label>Address:</label>
        <input type="text" name="group_address" required>

        <label>Phone:</label>
        <input type="text" name="group_phone" required>

        <label>Group Photo:</label>
        <input type="file" name="group_photo" accept="image/*" required>

        <label>Number of Members:</label>
        <input type="number" id="member_count" name="member_count" min="1" required oninput="showMembers()">

        <div id="members_container"></div>

        <input type="submit" value="Submit Group">
    </form>
</body>
</html>
