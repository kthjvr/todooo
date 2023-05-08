


const sidebar = document.querySelector(".sidebar");
const menu = document.querySelector("#menu");

const main = document.querySelector(".main");

const menu_container = document.querySelector(".menu-container");
const logout_container = document.querySelector(".logout-container");

const icon_logout = document.querySelector(".icon-logout");

const search = document.querySelector("#search");
const dashboard = document.querySelector("#dashboard");
const today = document.querySelector("#today");
const important = document.querySelector("#important");
const booklog = document.querySelector("#booklog");
const add = document.querySelector("#add");
const category = document.querySelector("#category");
const trash = document.querySelector("#trash");


let previousToggled = null;
let currentToggled = null;

search.addEventListener("click", (e) => {
  toggleMenu(search);
  window.location.href = "../frontend/tasks.php";
});

dashboard.addEventListener("click", (e) => {
  toggleMenu(dashboard);
  window.location.href = "../frontend/dashboard.php";
});

today.addEventListener("click", (e) => {
  toggleMenu(today);
  window.location.href = "../frontend/tasks-today.php";
});

important.addEventListener("click", (e) => {
  toggleMenu(important);
  window.location.href = "../frontend/tasks-starred.php";
});

booklog.addEventListener("click", (e) => {
  toggleMenu(booklog);
  window.location.href = "../frontend/tasks-completed.php";
});

category.addEventListener("click", (e) => {
  toggleMenu(category);
  window.location.href = "../frontend/category.php";
});

trash.addEventListener("click", (e) => {
  toggleMenu(trash);
  window.location.href = "../frontend/tasks-trash.php";
});

add.addEventListener("click", (e) => {
  toggleMenu(add);
  window.location.href = "../frontend/addTask.php";
});

icon_logout.addEventListener("click", (e) => {
  window.location.href='../index.html';
});

const toggleMenu = (button) => {
  if (previousToggled && button !== menu) {
    untoggleMenu(previousToggled);
  }

  button.classList.add("toggled");
  button.style.backgroundColor = "#F9C5D5";

  if (button !== menu) {
    previousToggled = button;
  }
};

const untoggleMenu = (button) => {
  button.classList.remove("toggled");
  button.style.backgroundColor = "#F9C5D5";
};

menu.addEventListener("click", (e) => {
  sidebar.classList.contains("active") ? closeMenu() : openMenu();
});

const openMenu = () => {
  sidebar.classList.add("active");
  sidebar.style.width = "250px";

  toggleMenu(menu);

  let menu_logo = document.createElement("img");
  menu_logo.id = "menu-logo";
  menu_logo.src = "../images/icon.png";
  menu_logo.style.width = "60px";
  menu_container.style.paddingLeft = "15px";
  menu_container.insertBefore(menu_logo, menu_container.childNodes[0]);

  let p_search = document.createElement("p");
  p_search.id = "p-search";
  p_search.innerHTML = "All Tasks";
  search.style.width = "220px";
  search.style.justifyContent = "left";
  search.appendChild(p_search);

  let p_dash = document.createElement("p");
  p_dash.id = "p-dashboard";
  p_dash.innerHTML = "Dashboard";
  dashboard.style.width = "220px";
  dashboard.style.justifyContent = "left";
  dashboard.appendChild(p_dash);

  let p_today = document.createElement("p");
  p_today.id = "p-today";
  p_today.innerHTML = "Today";
  today.style.width = "220px";
  today.style.justifyContent = "left";
  today.appendChild(p_today);

  let p_important = document.createElement("p");
  p_important.id = "p-important";
  p_important.innerHTML = "Important";
  important.style.width = "220px";
  important.style.justifyContent = "left";
  important.appendChild(p_important);

  let p_booklog = document.createElement("p");
  p_booklog.id = "p-booklog";
  p_booklog.innerHTML = "Archive";
  booklog.style.width = "220px";
  booklog.style.justifyContent = "left";
  booklog.appendChild(p_booklog);

  let p_trash = document.createElement("p");
  p_trash.id = "p-trash";
  p_trash.innerHTML = "Trash";
  trash.style.width = "220px";
  trash.style.justifyContent = "left";
  trash.appendChild(p_trash);

  let p_category = document.createElement("p");
  p_category.id = "p-category";
  p_category.innerHTML = "Categories";
  category.style.width = "220px";
  category.style.justifyContent = "left";
  category.appendChild(p_category);

  let p_add = document.createElement("p");
  p_add.id = "p-add";
  p_add.innerHTML = "Add Task";
  add.style.width = "220px";
  add.style.justifyContent = "left";
  add.appendChild(p_add);

  icon_logout.style.width = "100%";


  // let user_container = document.createElement("div");
  // user_container.id = "user-container";

  
  // let user_name = document.createElement("p");
  // user_name.id = "user-name";
  // user_name.innerHTML = "<?=$_SESSION['username']?>";

  // let user_role = document.createElement("p");
  // user_role.id = "user-role";
  // user_role.innerHTML = "Veterinarian";

  // user_container.appendChild(user_name);
  // user_container.appendChild(user_role);

  // logout_container.insertBefore(logout_container.childNodes[0]);

  // let logout_photo = document.createElement("img");
  // logout_photo.id = "logout-photo";
  // logout_photo.src = "https://github.com/diegoafv.png";
  // logout_container.style.paddingLeft = "15px";
  // logout_container.insertBefore(logout_photo, logout_container.childNodes[0]);

  main.style.width = "calc(100% - 250px)";
};

const closeMenu = () => {
  menu_container.removeChild(document.getElementById("menu-logo"));
  menu_container.style.paddingLeft = "0px";

  untoggleMenu(menu);

  search.removeChild(document.getElementById("p-search"));
  search.style.width = "50px";
  search.style.justifyContent = "center";

  dashboard.removeChild(document.getElementById("p-dashboard"));
  dashboard.style.width = "50px";
  dashboard.style.justifyContent = "center";

  today.removeChild(document.getElementById("p-today"));
  today.style.width = "50px";
  today.style.justifyContent = "center";

  important.removeChild(document.getElementById("p-important"));
  important.style.width = "50px";
  important.style.justifyContent = "center";

  booklog.removeChild(document.getElementById("p-booklog"));
  booklog.style.width = "50px";
  booklog.style.justifyContent = "center";

  category.removeChild(document.getElementById("p-category"));
  category.style.width = "50px";
  category.style.justifyContent = "center";

  trash.removeChild(document.getElementById("p-trash"));
  trash.style.width = "50px";
  trash.style.justifyContent = "center";

  add.removeChild(document.getElementById("p-add"));
  add.style.width = "50px";
  add.style.justifyContent = "center";

  // logout_container.removeChild(document.getElementById("logout-photo"));
  // logout_container.removeChild(document.getElementById("user-container"));
  // logout_container.style.paddingLeft = "0px";

  icon_logout.style.width = "100%";

  sidebar.classList.remove("active");
  sidebar.style.width = "78px";

  main.style.width = "calc(100% - 78px)";
};
