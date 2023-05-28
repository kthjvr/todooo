const sidebar = document.querySelector(".sidebar");
const menu = document.querySelector("#menu");

const main = document.querySelector(".main");

const menu_container = document.querySelector(".menu-container");
const logout_container = document.querySelector(".logout-container");
const account = document.querySelector("#logout-photo");

const icon_logout = document.querySelector(".icon-logout");

const search = document.querySelector("#search");
const dashboard = document.querySelector("#dashboard");
const today = document.querySelector("#today");
const important = document.querySelector("#important");
const due = document.querySelector("#due");
const booklog = document.querySelector("#booklog");
const add = document.querySelector("#add");
const category = document.querySelector("#category");
const trash = document.querySelector("#trash");
const assign = document.querySelector("#assign");

search.addEventListener("click", (e) => {
  window.location.href = "../frontend/tasks_v2.php";
});

dashboard.addEventListener("click", (e) => {
  window.location.href = "../frontend/dashboard.php";
});

today.addEventListener("click", (e) => {
  window.location.href = "../frontend/tasks-today.php";
});

important.addEventListener("click", (e) => {
  window.location.href = "../frontend/tasks-starred.php";
});

due.addEventListener("click", (e) => {
  window.location.href = "../frontend/task-due.php";
});

booklog.addEventListener("click", (e) => {
  window.location.href = "../frontend/tasks-completed.php";
});

category.addEventListener("click", (e) => {
  window.location.href = "../frontend/category.php";
});

trash.addEventListener("click", (e) => {
  window.location.href = "../frontend/tasks-trash.php";
});

assign.addEventListener("click", (e) => {
  window.location.href = "../frontend/tasks-assign.php";
});

add.addEventListener("click", (e) => {
  window.location.href = "../frontend/addTask.php";
});

icon_logout.addEventListener("click", (e) => {
  window.location.href = '../index.html';
});

// Open the menu by default
openMenu();

function openMenu() {
  sidebar.classList.add("active");
  sidebar.style.width = "250px";

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
  p_important.innerHTML = "Favorites";
  important.style.width = "220px";
  important.style.justifyContent = "left";
  important.appendChild(p_important);

  let p_due = document.createElement("p");
  p_due.id = "p-due";
  p_due.innerHTML = "Task Overdue";
  due.style.width = "220px";
  due.style.justifyContent = "left";
  due.appendChild(p_due);

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

  let p_assign = document.createElement("p");
  p_assign.id = "p-assign";
  p_assign.innerHTML = "Assigned to me";
  assign.style.width = "220px";
  assign.style.justifyContent = "left";
  assign.appendChild(p_assign);

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

  icon_logout.style.width = "25%";

  let user_container = document.createElement("div");
  user_container.id = "user-container";

  let user_name = document.createElement("p");
  user_name.id = "user-name";
  user_name.innerHTML = username;

  let user_role = document.createElement("p");
  user_role.id = "user-role";
  user_role.innerHTML = useremail;

  user_container.appendChild(user_name);
  user_container.appendChild(user_role);

  logout_container.insertBefore(user_container, logout_container.childNodes[0]);

  let logout_photo = document.createElement("img");
  logout_photo.id = "logout-photo";
  logout_photo.src = avatar;
  logout_photo.className = "logout-photo";
  logout_photo.style.cursor = "pointer";
  logout_container.style.paddingLeft = "15px";
  logout_container.insertBefore(logout_photo, logout_container.childNodes[0]);

  logout_photo.addEventListener("click", (e) => {
    window.location.href = "../frontend/account2.php";
  });

  main.style.width = "calc(100% - 80px)";
}
