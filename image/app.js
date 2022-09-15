/**
 * Avatars endpoint
 *
 * @author      Anton Lukin <anton@lukin.me>
 * @license     MIT License
 * @since       2.0
 */

const express = require('express');
const avatars = require('adorable-avatars');


let app = express();


// Start avatars middleware
app.use('/', avatars);


// Custom 404 error
app.use(function(req, res, next) {
  res.setHeader('content-type', 'text/plain');
  res.status(404).send('Page not found');
});


// Custom 500 error
app.use(function(err, req, res, next) {
  res.setHeader('content-type', 'text/plain');
  res.status(500).send('Internal server error');
});


// Let's roll
app.listen(process.env.PORT || 3000);
