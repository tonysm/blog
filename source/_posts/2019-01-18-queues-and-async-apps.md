---
extends: _layouts.post
section: content
title: Queues and Async Apps
date: 2019-01-18
description: Queues and Async Apps
featured: true
categories: [laravel,queues,websockets]
---

Right after I posted the [video](https://www.youtube.com/watch?v=GtphrhnFwZQ) where I introduce the [Laravel WebSockets Package](https://docs.beyondco.de/laravel-websockets/), I got a request to maybe talk more about a preview of an old talk I had on my YouTube channel. Decided to record it and share it.

In this talk I walk-through a problem of a server provisioning application, where we need to deal with long-running operations (like install dependencies in a server), and how to approach that using Queues and Workers. Then we jump in to enrich the UI with some real-time feedback using WebSockets.
    
Here it is:

<iframe width="560" height="315" src="https://www.youtube.com/embed/mhmkap7jdu8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

Hope you like it.
