<li>
    <input type="checkbox" class="category-checkbox" id="category-<?= $category->id ?>" <?= isset($this->categories) && !empty(array_filter($this->categories)) && in_array($category->id, $this->categories) ? 'checked' : '' ?> name="categories[]" value="<?= $category->id ?>"/>

    <label for="category-<?= $category->id ?>" class="checkbox-filter ">
        <span class="filter-category-name">
            <?= $category->name ?>

        </span>
            <?php if (isset($category['children']) && !empty($category['children'])){ ?>
                <a href="#" class="spoiler">
                    <span class='badge'><?= $category->count ?> <i class="fa fa-angle-down"></i></span>
                </a>
            <?php } else { ?>
                <span class="badge"><?= $category->count ?></span>
            <?php } ?>
    </label>

    <?php if (isset($category['children']) && !empty($category['children'])): ?>

        <ul class="child">
            <?= $this->getMenuHtml($category['children']) ?>
        </ul>
    <?php endif; ?>
</li>