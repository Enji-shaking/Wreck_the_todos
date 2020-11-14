
<nav class="nav flex-column nav-tabs navbar-expand-lg flex-shirnk-1">
  <a class="nav-link" id="today"><i class="fas fa-sun"></i>  <span class="text"> Today </span>  </a>
  <a class="nav-link" id="nextSeven"><i class="fas fa-star"></i>  <span class="text"> Next Seven Days </span>  </a>
  <a class="nav-link" id="allTask"><i class="fas fa-tasks"></i>  <span class="text"> All Tasks </span>  </a>
  <hr>
  <form id="addCategory">
    <input id="addCategoryItem" class="" type="text" maxlength="100" placeholder="New list" value="">
  </form>
  <h6 class="alert text-danger font-italic p-1 font-size-12 d-none" role="alert" id="alertNoCategoryName">
      * Must enter a name for the category!
  </h6>

  <div id="leftNavCategories">
    <?php while( $row = $results_catagories->fetch_assoc() ): ?>
      <a class="nav-link">
        <i class="fas fa-eraser btn btn-sm deleteCategory"></i>
        <i class="fas fa-bullseye"></i>
        <span class="text"> <?php echo $row['category']; ?> </span>
        <p class="d-none idcategory"> <?php echo $row['idcategory']; ?> </p>
      </a>
    <?php endwhile; ?>
  </div>
  
  
  
</nav>