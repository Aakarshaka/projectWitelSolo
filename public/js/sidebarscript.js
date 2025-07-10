const toggleButton = document.getElementById('toggle-btn')
const sidebar = document.getElementById('sidebar')

// Saat load halaman, cek localStorage
if(localStorage.getItem('sidebarClosed') === 'true'){
  sidebar.classList.add('close')
  toggleButton.classList.add('rotate')
}

function toggleSidebar(){
  sidebar.classList.toggle('close')
  toggleButton.classList.toggle('rotate')
  
  // Simpan status ke localStorage
  if(sidebar.classList.contains('close')){
    localStorage.setItem('sidebarClosed', 'true')
  } else {
    localStorage.setItem('sidebarClosed', 'false')
  }

  closeAllSubMenus()
}

function toggleSubMenu(button){
  if(!button.nextElementSibling.classList.contains('show')){
    closeAllSubMenus()
  }

  button.nextElementSibling.classList.toggle('show')
  button.classList.toggle('rotate')

  if(sidebar.classList.contains('close')){
    sidebar.classList.toggle('close')
    toggleButton.classList.toggle('rotate')
    localStorage.setItem('sidebarClosed', 'false')
  }
}

function closeAllSubMenus(){
  Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
    ul.classList.remove('show')
    ul.previousElementSibling.classList.remove('rotate')
  })
}
