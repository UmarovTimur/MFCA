// ydeROEcQGJUoe^8sh6vCLP5%

// eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjIsIm5hbWUiOiJ0aW0zNDUiLCJpYXQiOjE3MzY4NjM1NjQsImV4cCI6MTg5NDU0MzU2NH0.pyVCb35rod1OYkzRqeJtP9pSC6iYSD_xXy9B9rUkcYs
// jwt 
// const username = "your-username";
// const password = "your-password"; // только для Basic Auth (не рекомендуется в продакшене)

const apiUrl = "https://mfca.uzlatin.com//wp-json/wp/v2/posts";
const token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjIsIm5hbWUiOiJ0aW0zNDUiLCJpYXQiOjE3MzY4NjM1NjQsImV4cCI6MTg5NDU0MzU2NH0.pyVCb35rod1OYkzRqeJtP9pSC6iYSD_xXy9B9rUkcYs";   // используйте, если настроен JWT
const mysql = require('mysql2/promise');

async function connectToDatabase() {
    try {
        const connection = await mysql.createConnection({
            host: '195.35.59.22',
            user: 'u684340623_lib24',
            password: 'Xg?>/G+9s',
            database: 'u684340623_lib24'
        });

        const [rows] = await connection.execute('SELECT * FROM books_az');
        // console.log('Результаты:', rows);

        rows.forEach(row => {
            if (row.id == 27) {
                console.log(row.title);
            }
        });

        await connection.end();
    } catch (err) {
        console.error('Ошибка:', err.message);
    }
}

// Функция для создания поста
async function createPost(title, options) {
    try {
        const response = await fetch(apiUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`, // Для JWT
                // "Authorization": `Basic ${btoa(username + ":" + password)}`, // Для Basic Auth
            },
            body: JSON.stringify({
                title: title,
                ...options,
            }),
        });

        if (!response.ok) {
            throw new Error(`Error: ${response.status} - ${response.statusText}`);
        }

        const data = await response.json();
        console.log("Post created successfully:", data.title);
        return data;
    } catch (error) {
        console.error("Failed to create post:", error);
    }
}

function getCategoreisId() {
    fetch("https://mfca.uzlatin.com/wp-json/wp/v2/categories")
        .then(response => response.json())
        .then(data => {
            console.log(data);
        })
        .catch(error => {
            console.error("Failed to fetch categories:", error);
        });
}

// connectToDatabase();
// createPost("Заголовок поста", );
// getCategoreisId();