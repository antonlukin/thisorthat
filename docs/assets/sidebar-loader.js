(function() {
  // Prettify code blocks
  if (window.hljs) {
    hljs.initHighlightingOnLoad();
  }

  var markdown = document.getElementById('markdown');

  // Find all h2 elements
  var titles = markdown.querySelectorAll('h2');

  if (titles.length > 0) {
    var sidebar = document.createElement('aside');
    sidebar.classList.add('sidebar');

    var menu = document.createElement('nav');
    sidebar.appendChild(menu);

    // Loop through titles and make sidebar
    for (var i = 0, title; title = titles[i]; i++) {
      var link = document.createElement('a');

      link.setAttribute('href', '#' + title.id);
      link.innerHTML = title.textContent;

      menu.appendChild(link);
    }

    document.querySelector('.wrap').appendChild(sidebar);
  }
})();
