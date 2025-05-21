# MessageApp

<p><b>MessageApp</b> - это система обработки сообщений через очередь (Message Queue).</p>

<p>Для запуска проекта нужно склонировать его коммандой <strong>git clone</strong>. После перейти в главную директорию проекта и в терминале прописать <strong>docker-compose up -d</strong>. После перейти перейти по адресу <strong>http://localhost:80</strong>. Также можно получить доступ к БД через <strong>phpmyadmin</strong>, для этого нужно перейти по адресу <strong>http://localhost:1500</strong>, логин и пароль прописаны в файле <strong>/config/database.php</strong>.</p>
<br>
<p>⚠️ У проекта есть момент, который проявляется в основном при первой сборке контейнеров, заключается он в том, что контейнер <strong>php-worker</strong> может запустится раньше контейнера <strong>mysql</strong>, что приведёт к ошибке <strong>Connection refused</strong>, после чего нужно перезагружать контейнер <strong>php-worker</strong>.
</p>

<h4>Ниже представлен интерфейс главной страницы:</h4>

![image](https://github.com/user-attachments/assets/68b2ff62-48fa-4e82-bb52-facba174a9e1)
