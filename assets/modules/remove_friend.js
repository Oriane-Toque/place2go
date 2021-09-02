// Delete a friend
export function removeFriend(item) {
  let url = item.getAttribute("data-url");
  let nickname = item.getAttribute("data-nickname");

  if (confirm("Supprimer " + nickname + " ?")) {
    const xhttp = new XMLHttpRequest();
    xhttp.open("GET", url);
    xhttp.send();
    item.closest(".chip").remove();
  }
}
