<!-- The static Swagger UI page that loads the UI bundle -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Web Programming API</title>
    <link rel="stylesheet" type="text/css" href="swagger-ui.css" >
    <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />
    <style>
      html
      {
        box-sizing: border-box;
        overflow: -moz-scrollbars-vertical;
        overflow-y: scroll;
      }
      *,
      *:before,
      *:after
      {
        box-sizing: inherit;
      }
      body
      {
        margin:0;
        background: #fac2f3ff;
      }
    </style>
  </head>

  <body>
    <div id="swagger-ui"></div>
    <script src="swagger-ui-bundle.js"> </script>
    <script src="swagger-ui-standalone-preset.js"> </script>
    <script>
    window.onload = function() { //Browser loads this HTML, JS initializes Swagger UI, fetches swagger.php, and renders the API docs with try-it-out support
      // Begin Swagger UI call region
      const ui = SwaggerUIBundle({
        url: "swagger.php",
        dom_id: '#swagger-ui',
        deepLinking: true,
        presets: [
          SwaggerUIBundle.presets.apis,
          SwaggerUIStandalonePreset
        ],
        plugins: [
          SwaggerUIBundle.plugins.DownloadUrl
        ],
        layout: "StandaloneLayout"
      })
      // End Swagger UI call region
      window.ui = ui
    }
  </script>
  </body>
</html>
