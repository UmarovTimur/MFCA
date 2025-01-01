// ydeROEcQGJUoe^8sh6vCLP5%


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
        console.log('Результаты:', rows);

        await connection.end();
    } catch (err) {
        console.error('Ошибка:', err.message);
    }
}

connectToDatabase();

