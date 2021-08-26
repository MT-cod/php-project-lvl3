## Учебный проект «Анализатор страниц» в рамках курса Hexlet (PHP-разработчик)

[![Actions Status](https://github.com/MT-cod/php-project-lvl3/workflows/hexlet-check/badge.svg)](https://github.com/MT-cod/php-project-lvl3/actions)
[![PHP%20CI](https://github.com/MT-cod/php-project-lvl3/workflows/PHP%20CI/badge.svg)](https://github.com/MT-cod/php-project-lvl3/actions)
<br>
[![Code Climate](https://codeclimate.com/github/MT-cod/php-project-lvl3/badges/gpa.svg)](https://codeclimate.com/github/MT-cod/php-project-lvl3)
[![Issue Count](https://codeclimate.com/github/MT-cod/php-project-lvl3/badges/issue_count.svg)](https://codeclimate.com/github/MT-cod/php-project-lvl3/issues)
[![Test Coverage](https://codeclimate.com/github/MT-cod/php-project-lvl3/badges/coverage.svg)](https://codeclimate.com/github/MT-cod/php-project-lvl3/coverage)


<h2>Цель</h2>
<p>Третий проект –&nbsp;полноценный веб-сайт на базе фреймворка <strong>Laravel</strong>. Здесь отрабатываются базовые принципы построения современных сайтов на <strong>MVC</strong>-архитектуре: работа с роутингом, обработчиками запросов и шаблонизатором, взаимодействие с базой данных через <strong>ORM</strong>.</p>

<p>Кроме кода сайта, веб-разработка включает в себя важные инфраструктурные элементы, для запуска сайта нужен <strong>веб-сервер</strong> и установленная база данных. В свою очередь работа по сети опирается на базовое понимание работы протокола TCP и знакомство с понятием ip-адреса и порта.</p>

<p>Когда сайт готов, его нужно выложить в публичный доступ или, как говорят программисты: "задеплоить в продакшен". Выкладка готового кода - неотъемлемая часть разработки. Существует множество различных подходов и требований к тому как это делать эффективно. В этом проекте отрабатывается наиболее автоматизированный способ, с помощью сервиса Heroku. Это хостинг, работающий по модели PaaS (платформа как сервис), он сам отвечает за инфраструктуру и требует определённого способа организации кода для обеспечения масштабируемости.</p>

<h2>Описание</h2>
<p>Анализатор страниц – сайт, который анализирует указанные страницы на SEO пригодность по аналогии с <a href="https://varvy.com/pagespeed/" target="_blank" rel="nofollow">varvy</a></p>

## Развёрнутый проект на Heroku:
<a href="https://mt-cod-php-project-lvl3.herokuapp.com/">mt-cod-php-project-lvl3.herokuapp.com</a>

## Готовый docker-образ с проектом:
<a href="https://hub.docker.com/r/mtcod/php-project-lvl3">mtcod/php-project-lvl3</a>

######Пример загрузки и запуска контейнера проекта:
docker run -p 80:8000 -d mtcod/php-project-lvl3 php /srv/php-project-lvl3/artisan serve --host 0.0.0.0
