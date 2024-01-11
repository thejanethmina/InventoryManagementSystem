  // Function to handle pagination for a specific table
  const handlePagination = (tableId, rowsPerPage) => {
    // Select the table and its rows
    const table = document.getElementById(tableId);
    const rows = table.querySelectorAll('tbody tr');

    // Set the number of rows per page and initialize the current page to 1
    // const rowsPerPage = 2;
    let currentPage = 1;

    // Create a new div to hold the page navigation buttons
    const pageNav = document.createElement('div');
    pageNav.classList.add('page-nav');
    table.parentNode.insertBefore(pageNav, table.nextSibling);

    // Function to show the rows of the current page
    const showPage = (page) => {
      // Set the current page to the one specified
      currentPage = page;

      // Calculate the start and end indexes of the rows to display
      const startIndex = (page - 1) * rowsPerPage;
      const endIndex = startIndex + rowsPerPage;

      // Hide all rows
      for (let i = 0; i < rows.length; i++) {
        rows[i].style.display = 'none';
      }

      // Display the rows within the selected range
      for (let i = startIndex; i < endIndex; i++) {
        if (rows[i]) {
          rows[i].style.display = 'table-row';
        }
      }

      // Update the current page button
      const buttons = document.querySelectorAll('.page-nav button');
      for (let i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove('current-page');
      }

     const pageButton = document.querySelector(`#${tableId} #page-${currentPage}`);
      if (pageButton) {
        pageButton.classList.add('current-page');
      }


    };

    // Function to create the page buttons
    const createPageButtons = () => {
      // Calculate the total number of pages
      const pages = Math.ceil(rows.length / rowsPerPage);

      // Create a button for each page
      for (let i = 1; i <= pages; i++) {
        const button = document.createElement('button');
        // Set the ID of the button to "page-x", where x is the page number
        button.id = `page-${i}`;
        // Set the button text to the page number
        button.textContent = i;
        // Set the class of the first button to "current-page"
        if (i === 1) {
          button.classList.add('current-page');
        }
        // Add an event listener to the button that calls showPage() with the corresponding page number
        button.addEventListener('click', () => {
          showPage(i);
        });
        // Append the button to the page navigation div
        pageNav.appendChild(button);
      }
    };

    // Call createPageButtons() to create the initial set of page buttons
    createPageButtons();
    // Call showPage() with an argument of 1 to display the first page of rows
    showPage(1);
  };
