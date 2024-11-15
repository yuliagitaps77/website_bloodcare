$(document).ready(function() {
    $(".dropdown-trigger").dropdown();
    $('.sidenav').sidenav();
  });
  document.addEventListener('DOMContentLoaded', function () {
    // Select the sidebar items
    const sidebarItems = document.querySelectorAll('.bordered');
  
    // Set default highlight on "Dashboard"
    document.getElementById('dashboard').classList.add('selected');
  
    // Add click event listener to each sidebar item
    sidebarItems.forEach(item => {
        item.addEventListener('click', function () {
            // Remove "selected" class from all items
            sidebarItems.forEach(i => i.classList.remove('selected'));
  
            // Add "selected" class to the clicked item
            this.classList.add('selected');
        });
    });
  });