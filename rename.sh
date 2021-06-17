#!/bin/bash

set -u

# ===================================================
# プラグイン名
# ===================================================
# アッパーキャメルケース（単語の先頭を大文字、それ以外は小文字） BcCrud
export PLUGIN_NAME_1="ExSample"

# スネークケース（単語の間をアンダーバー区切り、全て小文字） bc_crud
export PLUGIN_NAME_2="ex_sample"

# キャメルケース（単語の先頭を大文字、それ以外は小文字） bcCrud
export PLUGIN_NAME_3="exSample"

# 日本語名 サンプルプラグイン
export PLUGIN_NAME_4="さんぷる"


find . -type f -name "*.php" -o -name "*.js" -o -name "*.css" -o -name "*.md" | xargs sed -i '' -e s/BcCrud/${PLUGIN_NAME_1}/g -e s/bc_crud/${PLUGIN_NAME_2}/g -e s/bcCrud/${PLUGIN_NAME_3}/g -e s/サンプルプラグイン/${PLUGIN_NAME_4}/g

mv Config/Schema/bc_crud_categories.php Config/Schema/${PLUGIN_NAME_2}_categories.php
mv Config/Schema/bc_crud_contents.php Config/Schema/${PLUGIN_NAME_2}_contents.php
mv Config/Schema/bc_crud_posts_bc_crud_tags.php Config/Schema/${PLUGIN_NAME_2}_posts_${PLUGIN_NAME_2}_tags.php
mv Config/Schema/bc_crud_posts.php Config/Schema/${PLUGIN_NAME_2}_posts.php
mv Config/Schema/bc_crud_tags.php Config/Schema/${PLUGIN_NAME_2}_tags.php

mv Controller/BcCrudAppController.php Controller/${PLUGIN_NAME_1}AppController.php
mv Controller/BcCrudCategoriesController.php Controller/${PLUGIN_NAME_1}CategoriesController.php
mv Controller/BcCrudContentsController.php Controller/${PLUGIN_NAME_1}ContentsController.php
mv Controller/BcCrudController.php Controller/${PLUGIN_NAME_1}Controller.php
mv Controller/BcCrudPostsController.php Controller/${PLUGIN_NAME_1}PostsController.php
mv Controller/BcCrudTagsController.php Controller/${PLUGIN_NAME_1}TagsController.php

mv Event/BcCrudControllerEventListener.php Event/${PLUGIN_NAME_1}ControllerEventListener.php
mv Event/BcCrudHelperEventListener.php Event/${PLUGIN_NAME_1}HelperEventListener.php
mv Event/BcCrudModelEventListener.php Event/${PLUGIN_NAME_1}ModelEventListener.php
mv Event/BcCrudViewEventListener.php Event/${PLUGIN_NAME_1}ViewEventListener.php

mv Model/BcCrudAppModel.php Model/${PLUGIN_NAME_1}AppModel.php
mv Model/BcCrudCategory.php Model/${PLUGIN_NAME_1}Category.php
mv Model/BcCrudContent.php Model/${PLUGIN_NAME_1}Content.php
mv Model/BcCrudPost.php Model/${PLUGIN_NAME_1}Post.php
mv Model/BcCrudTag.php Model/${PLUGIN_NAME_1}Tag.php

rm -rf View/${PLUGIN_NAME_1}/
rm -rf View/${PLUGIN_NAME_1}Categories/
rm -rf View/${PLUGIN_NAME_1}Contents/
rm -rf View/${PLUGIN_NAME_1}Posts/
rm -rf View/${PLUGIN_NAME_1}Tags/

mv View/BcCrud/ View/${PLUGIN_NAME_1}/
mv View/BcCrudCategories/ View/${PLUGIN_NAME_1}Categories/
mv View/BcCrudContents/ View/${PLUGIN_NAME_1}Contents/
mv View/BcCrudPosts/ View/${PLUGIN_NAME_1}Posts/
mv View/BcCrudTags/ View/${PLUGIN_NAME_1}Tags/

rm -rf View/Elements/admin/${PLUGIN_NAME_2}_categories/
rm -rf View/Elements/admin/${PLUGIN_NAME_2}_posts/
rm -rf View/Elements/admin/${PLUGIN_NAME_2}_tags/

mv View/Elements/admin/bc_crud_categories/ View/Elements/admin/${PLUGIN_NAME_2}_categories/
mv View/Elements/admin/bc_crud_posts/ View/Elements/admin/${PLUGIN_NAME_2}_posts/
mv View/Elements/admin/bc_crud_tags/ View/Elements/admin/${PLUGIN_NAME_2}_tags/

mv View/Elements/admin/helps/bc_crud_categories_form.php View/Elements/admin/helps/${PLUGIN_NAME_2}_categories_form.php
mv View/Elements/admin/helps/bc_crud_categories_index.php View/Elements/admin/helps/${PLUGIN_NAME_2}_categories_index.php
mv View/Elements/admin/helps/bc_crud_posts_form.php View/Elements/admin/helps/${PLUGIN_NAME_2}_posts_form.php
mv View/Elements/admin/helps/bc_crud_posts_index.php View/Elements/admin/helps/${PLUGIN_NAME_2}_posts_index.php
mv View/Elements/admin/helps/bc_crud_tags_form.php View/Elements/admin/helps/${PLUGIN_NAME_2}_tags_form.php
mv View/Elements/admin/helps/bc_crud_tags_index.php View/Elements/admin/helps/${PLUGIN_NAME_2}_tags_index.php

mv View/Elements/admin/searches/bc_crud_categories_index.php View/Elements/admin/searches/${PLUGIN_NAME_2}_categories_index.php
mv View/Elements/admin/searches/bc_crud_posts_index.php View/Elements/admin/searches/${PLUGIN_NAME_2}_posts_index.php
mv View/Elements/admin/searches/bc_crud_tags_index.php View/Elements/admin/searches/${PLUGIN_NAME_2}_tags_index.php

mv View/Helper/BcCrudHelper.php View/Helper/${PLUGIN_NAME_1}Helper.php

mv webroot/css/admin/bc_crud_admin.css webroot/css/admin/${PLUGIN_NAME_2}_admin.css

rm -rf webroot/js/admin/${PLUGIN_NAME_2}_categories/
rm -rf webroot/js/admin/${PLUGIN_NAME_2}_posts/
rm -rf webroot/js/admin/${PLUGIN_NAME_2}_tags/

mv webroot/js/admin/bc_crud_categories/ webroot/js/admin/${PLUGIN_NAME_2}_categories/
mv webroot/js/admin/bc_crud_posts/ webroot/js/admin/${PLUGIN_NAME_2}_posts/
mv webroot/js/admin/bc_crud_tags/ webroot/js/admin/${PLUGIN_NAME_2}_tags/

rm -rf ../${PLUGIN_NAME_1}
mv ../BcCrud/ ../${PLUGIN_NAME_1}
