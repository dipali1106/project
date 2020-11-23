function showAdd(){
        var x = document.getElementById("addMovie");
        var y = document.getElementById("deleteMovie");
        var z= document.getElementById("editMovie");
        var titles= document.getElementById("movies-title");
        if (x.style.display === "none") {
          x.style.display = "block";
          y.style.display="none";
          z.style.display="none";
          titles.style.display="block";
          editForm.style.display="none";
        } else {
          x.style.display = "none";
        }
      }
      function showEdit(){
        var x = document.getElementById("addMovie");
        var y = document.getElementById("deleteMovie");
        var z= document.getElementById("editMovie");
        var titles= document.getElementById("movies-title");
        var editForm=document.getElementById("edit-movie");
        
        if (z.style.display === "none") {
          z.style.display = "block";
          x.style.display="none";
           y.style.display="none";
          titles.style.display="none";
        } else {
          z.style.display = "none";
          editForm.style.display="none";
          titles.style.display="block";
        }
      }
      function showDelete(){
        var x = document.getElementById("addMovie");
        var titles= document.getElementById("movies-title");
        var y = document.getElementById("deleteMovie");
        var z= document.getElementById("editMovie");
        
        
        if (y.style.display === "none") {
          titles.style.display= "none";
          x.style.display="none";
          z.style.display="none";
          y.style.display = "block";
        } 
       /* else {
          y.style.display = "none";
          titles.style.display= "block";
        }*/
      } 