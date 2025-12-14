<script>
  // Get a reference to the search form and search results div
  const form = document.getElementById('search-form');
  const resultsDiv = document.getElementById('search-results');

  // Submit form event handler
  form.addEventListener('submit', function (event) {
    event.preventDefault(); 
    const query = document.getElementById('search_query').value;
    const searchType = document.getElementById('search_type').value;

    
    performSearch(query, searchType);
  });

  // Function to perform the search
  function performSearch(query, searchType) {
    fetch('search.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ query, searchType }),
    })
      .then((response) => response.json())
      .then((data) => {
        
        resultsDiv.innerHTML = '';

        
        if (data.length > 0) {
          const ul = document.createElement('ul');
          data.forEach((result) => {
            const li = document.createElement('li');
            li.textContent = result; 
            ul.appendChild(li);
          });
          resultsDiv.appendChild(ul);
        } else {
          resultsDiv.textContent = 'No results found.';
        }
      })
      .catch((error) => {
        console.error('Error:', error);
      });
  }
</script>