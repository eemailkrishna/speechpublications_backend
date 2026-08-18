@extends('layouts.app')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
        font-family: sans-serif;
    }

    .hero-section {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1499951360447-b19be8fe80f5?auto=format&fit=crop&w=1200&q=80');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 60px;
        border-radius: 4px;
    }

    .card {
        border: none;
        border-radius: 0;
        transition: transform 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,.1)!important;
    }

    .card-title {
        font-weight: bold;
        color: #003366;
    }

    .sidebar-title {
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }

    .category-list .list-group-item {
        border: none;
        padding-left: 0;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        background: none;
    }

    .category-list .list-group-item:hover,
    .category-list .list-group-item.active {
        background-color: #e7f1ff;
        color: #0056b3;
        font-weight: 500;
    }

    .btn-primary {
        background-color: #0056b3;
        border: none;
        border-radius: 2px;
    }

    .meta-text {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .card-img-top {
        height: 200px;
        object-fit: cover;
    }

    .pagination {
        justify-content: center;
    }

    .loading {
        text-align: center;
        padding: 40px;
    }
    </style>
    @section('content')
    <article class="contact_page ">
        <div class="inner_banner" style="background:#003366;height:100px">
            <div class="wrapper">

            </div>

        </div>

        <section class="form_section section_with_bg mb-3">
            <div class="wrapped">


                <h2 class="fw-bold mb-4">News</h2>

                <!-- Featured News -->
                <div id="featuredNews" class="hero-section mb-5" style="display: none;">
                    <div class="col-md-7">
                        <h1 class="display-5 fw-bold" id="featuredTitle"></h1>
                        <p class="lead my-4" id="featuredDesc"></p>
                        <p class="meta-text text-white" id="featuredMeta"></p>
                        <a href="#" class="btn btn-primary px-4 py-2" id="featuredBtn">Read More</a>
                    </div>
                </div>

                <div class="row" style="margin-bottom: 100px;">
                    <div class="col-lg-9">
                        <h4 class="fw-bold mb-4">Latest News</h4>
                        
                        <div id="newsLoading" class="loading">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading news...</p>
                        </div>

                        <div id="newsContainer" class="row g-4" style="display: none;">
                            <!-- News cards will be inserted here -->
                        </div>

                        <!-- Pagination -->
                        <nav id="paginationContainer" style="display: none;" class="mt-5">
                            <ul class="pagination"></ul>
                        </nav>

                        <div id="noNews" class="alert alert-info text-center" style="display: none;">
                            No news available at the moment.
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="ps-lg-4">
                            <h5 class="fw-bold sidebar-title">Categories</h5>
                            <ul class="list-group category-list mb-5" id="categoriesList">
                                <li class="list-group-item active" data-category="all">All</li>
                                <li class="list-group-item" data-category="announcements">Announcements</li>
                                <li class="list-group-item" data-category="technology">Technology</li>
                                <li class="list-group-item" data-category="events">Events</li>
                                <li class="list-group-item" data-category="updates">Updates</li>
                            </ul>

                            <h5 class="fw-bold sidebar-title">Trending News</h5>
                            <div id="trendingNews" class="mb-4">
                                <!-- Trending news will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </article>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    let allNews = [];
    let currentPage = 1;
    let selectedCategory = 'all';

    // Fetch news from API
    function fetchNews(page = 1, category = 'all') {
        $('#newsLoading').show();
        $('#newsContainer').hide();
        $('#paginationContainer').hide();
        $('#noNews').hide();

        $.ajax({
            url: '/news?page=' + page + (category !== 'all' ? '&category=' + category : ''),
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                allNews = response.data;
                currentPage = response.current_page;
                displayNews(response);
                displayPagination(response);
                $('#newsLoading').hide();
            },
            error: function() {
                $('#newsLoading').hide();
                $('#noNews').text('Failed to load news').show();
            }
        });
    }

    // Display news cards
    function displayNews(response) {
        const container = $('#newsContainer');
        container.empty();

        if (response.data.length === 0) {
            $('#noNews').show();
            container.hide();
            return;
        }

        // Display featured news (first item)
        if (response.data.length > 0) {
            const featured = response.data[0];
            displayFeaturedNews(featured);
        }

        // Display all news cards
        response.data.forEach(function(news) {
            const imageUrl = news.image ? 
                'https://speechpublications.s3.amazonaws.com/news/' + news.image : 
                'https://via.placeholder.com/400x200?text=No+Image';
            
            const newsCard = `
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="${imageUrl}" class="card-img-top" alt="${news.title}">
                        <div class="card-body">
                            <h5 class="card-title">${news.title}</h5>
                            <p class="card-text small text-muted">${news.description.substring(0, 100)}...</p>
                            <p class="meta-text">${news.writer_name} | ${new Date(news.publish_date).toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric'})}</p>
                            <a href="#" class="btn btn-primary btn-sm px-3">Read More</a>
                        </div>
                    </div>
                </div>
            `;
            container.append(newsCard);
        });

        container.show();
        displayTrendingNews(response.data);
    }

    // Display featured news
    function displayFeaturedNews(news) {
        const imageUrl = news.image ? 
            'https://speechpublications.s3.amazonaws.com/news/' + news.image : 
            'https://images.unsplash.com/photo-1499951360447-b19be8fe80f5?auto=format&fit=crop&w=1200&q=80';

        $('#featuredNews').css({
            'background-image': `linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('${imageUrl}')`
        });

        $('#featuredTitle').text(news.title);
        $('#featuredDesc').text(news.description.substring(0, 150) + '...');
        $('#featuredMeta').text(`${news.writer_name} | ${new Date(news.publish_date).toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric'})}`);
        $('#featuredNews').show();
    }

    // Display pagination
    function displayPagination(response) {
        const container = $('#paginationContainer');
        const paginationList = container.find('ul');
        paginationList.empty();

        if (response.last_page <= 1) {
            container.hide();
            return;
        }

        // Previous button
        if (response.current_page > 1) {
            paginationList.append(`
                <li class="page-item">
                    <a class="page-link" href="#" onclick="goToPage(${response.current_page - 1}); return false;">Previous</a>
                </li>
            `);
        }

        // Page numbers
        for (let i = 1; i <= response.last_page; i++) {
            const activeClass = i === response.current_page ? 'active' : '';
            paginationList.append(`
                <li class="page-item ${activeClass}">
                    <a class="page-link" href="#" onclick="goToPage(${i}); return false;">${i}</a>
                </li>
            `);
        }

        // Next button
        if (response.current_page < response.last_page) {
            paginationList.append(`
                <li class="page-item">
                    <a class="page-link" href="#" onclick="goToPage(${response.current_page + 1}); return false;">Next</a>
                </li>
            `);
        }

        container.show();
    }

    // Display trending news in sidebar
    function displayTrendingNews(newsArray) {
        const container = $('#trendingNews');
        container.empty();

        newsArray.slice(0, 3).forEach(function(news) {
            const trendingItem = `
                <a href="#" class="d-block text-decoration-none text-dark small mb-3">
                    <strong>${news.title}</strong>
                </a>
            `;
            container.append(trendingItem);
        });
    }

    // Go to specific page
    function goToPage(page) {
        fetchNews(page, selectedCategory);
        window.scrollTo(0, 0);
    }

    // Category filter
    $(document).on('click', '#categoriesList .list-group-item', function() {
        $('#categoriesList .list-group-item').removeClass('active');
        $(this).addClass('active');
        selectedCategory = $(this).data('category');
        fetchNews(1, selectedCategory);
    });

    // Load news on page load
    $(document).ready(function() {
        fetchNews(1, 'all');
    });
    </script>

    @endsection