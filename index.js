const MYSQL = require('mysql2/promise');

// ydeROEcQGJUoe^8sh6vCLP5%
const HOST = '195.35.59.22';
const USER = 'u684340623_lib24';
const PASSWORD = 'Xg?>/G+9s';
const DATABASE = 'u684340623_lib24';
const URL = "https://mfca.uzlatin.com//wp-json/wp/v2/posts";
const TOKEN = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjIsIm5hbWUiOiJ0aW0zNDUiLCJpYXQiOjE3MzY4NjM1NjQsImV4cCI6MTg5NDU0MzU2NH0.pyVCb35rod1OYkzRqeJtP9pSC6iYSD_xXy9B9rUkcYs";   // используйте, если настроен JWT

const TABLE_NAME = 'books_az';


async function connectToDatabase() {
    try {
        const connection = await MYSQL.createConnection({
            host: HOST,
            user: USER,
            password: PASSWORD,
            database: DATABASE
        });

        const [rows] = await connection.execute(`SELECT * FROM ${TABLE_NAME} LIMIT 1`);

        const options = parseBook(rows[0]);
        createPost(options);

        // rows.forEach(async (row) => {
        //     const options = parseBook(row);
        //     createPost(options);
        // });
        // removeFeaturedImage(rows[0].id);



        await connection.end();
    } catch (err) {
        console.error('Ошибка:', err.message);
    }
}

// Функция для создания поста
async function createPost(options) {
    try {
        const response = await fetch(URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${TOKEN}`, // Для JWT
            },
            body: JSON.stringify({
                ...options,
            }),
        });


        if (!response.ok) {
            throw new Error(`Error: ${response.status} - ${response.statusText}`);
        }

        const data = await response.json();
        console.log("Post created successfully:", data.title);
        await removeFeaturedImage(data.id);


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
// createPost(title,);
function parseBook(book) {
    const type = TABLE_NAME.split('_')[0];
    const lang = TABLE_NAME.split('_')[1];

    return {
        title: book.title,
        status: "publish",
        meta: {
            fifu_image_url: `https://mukitob.com/${type}/covers/${lang}/${book.id}.jpg`, // Передаём URL изображения        
        },
        content: `
        <p>${book?.author}</p>
        <h3 class="wp-block-heading">${book.title}</h3>
        <p>${book.annotation ? `${book.annotation}` : `${book?.title2}`}</p>
    `,
        categories: [18, 17],
        featured_media: 447
    };
}


async function getImages() {
    try {
        const response = await fetch("https://mfca.uzlatin.com//wp-json/wp/v2/media", {
            method: "GET",
            headers: {
                "Authorization": `Bearer ${TOKEN}`,
                "Content-Type": "application/json"
            }
        });

        if (!response.ok) {
            throw new Error(`Failed to fetch images! Status: ${response.status}`);
        }

        const images = await response.json();
        console.log("Images:", images); // Вернёт массив изображений с их ID, URL и другой информацией

    } catch (error) {
        console.error("Error fetching images:", error);
    }
}


async function removeFeaturedImage(postId) {
    try {
        const response = await fetch(`${URL}/${postId}`, {
            method: "POST",
            headers: {
                "Authorization": `Bearer ${TOKEN}`,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                status: "publish",
            })
        });

        if (!response.ok) {
            throw new Error(`Failed to update post! Status: ${response.status}`);
        }

        const postData = await response.json();
        console.log("Featured image removed successfully:", postData.title);
    } catch (error) {
        console.error("Error removing featured image:", error);
    }
}





connectToDatabase();
// getCategoreisId();

// Пример использования
// getAllMedia(TOKEN);


// Пример использования
// getImages();

