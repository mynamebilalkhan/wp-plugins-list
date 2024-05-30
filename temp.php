<?php

/**
 * Template Name: Second Temp
 */
get_header();
?>

<style>
     .plugin-list-container {
          width: 100%;
          margin: 0 auto;
     }

     .plugin-table {
          width: 100%;
          border-collapse: collapse;
          margin-bottom: 20px;
     }

     .plugin-table th,
     .plugin-table td {
          border: 1px solid #ddd;
          padding: 8px;
          text-align: left;
     }

     .plugin-table th {
          background-color: #f2f2f2;
     }

     .plugin-table a {
          color: #0073aa;
          text-decoration: none;
     }

     .plugin-table a:hover {
          text-decoration: underline;
     }

     .pagination {
          display: flex;
          justify-content: center;
          margin-top: 20px;
     }

     .pagination a,
     .pagination .current-page,
     .pagination .ellipsis {
          margin: 0 5px;
          padding: 10px 15px;
          text-decoration: none;
          border: 1px solid #ddd;
          color: #0073aa;
     }

     .pagination .current-page {
          background-color: #0073aa;
          color: white;
          border: 1px solid #0073aa;
     }

     .pagination .ellipsis {
          padding: 0;
     }

     .pagination a:hover {
          background-color: #f2f2f2;
     }

     .container {
          max-width: 1180px;
          margin: 0 auto;
     }
</style>

<div class="container">
     <div class="plugin-list-container">
          <h1>WordPress Plugins</h1>
          <!-- Plugin list will be loaded here -->
     </div>
     <div class="pagination-container">
          <!-- Pagination links will be loaded here -->
     </div>
</div>

<?php get_footer(); ?>