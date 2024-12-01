<div class="bbn-overlay" id="bbn-databases-tree-container">
  <bbn-splitter orientation="horizontal">
    <bbn-pane :size="350" class="tree-db">
      <bbn-splitter orientation="vertical">
        <bbn-pane :size="40" class="bbn-w-100 bbn-vpadding bbn-c">
          <bbn-input v-model="search"
                     placeholder="<?= _("Filter tree") ?>"
                     style="width: 80%"
                     button-left="nf nf-fa-times"
                     action-left="clear"
                     :auto-hide-left="true"
                     button-right="nf nf-fa-search">
          </bbn-input>
        </bbn-pane>
        <bbn-pane>
          <div class="bbn-padding bbn-xl">
            <bbn-tree :select="get_description"
                      :source="root + 'tree_db'"
                      ref="tree">
            </bbn-tree>
          </div>
        </bbn-pane>
      </bbn-splitter>
    </bbn-pane>
    <bbn-pane>
      <bbn-splitter orientation="vertical">
        <bbn-pane :size="120" class="bbn-c">
          <div class="bbn-xxl bbn-h-50 bbn-middle"
               v-if="description">
            <i :class="'bbn-hmargin bbn-xxl ' + descriptionIcon"> </i>
            <bbn-input name="title"
                       v-model="description.name"
                       v-on:blur="rename()"
                       v-if="renaming"></bbn-input>
            <span v-text="description.name"
                  v-if="!renaming">
            </span>
            <i class="bbn-hmargin bbn-s bbn-p nf nf-fa-edit"
               @click="renaming = true; oldName = description.name;"
               v-if="!renaming">
            </i>
          </div>
          <div class="bbn-xxl bbn-h-50 bbn-middle"
               v-if="description">
            <bbn-input v-model="currentTitle"
                       v-on:focus="pretitle()"
                       v-on:blur="retitle()"
                       placeholder="<?= _("Give a user friendly title for your end users") ?>"
                       style="width: 80%">
            </bbn-input>
          </div>
          <div class="bbn-xxl bbn-h-100 bbn-c bbn-middle"
               v-else>
            <?= _("Select an element from the databases tree") ?>
          </div>
        </bbn-pane>
        <bbn-pane class="bbn-databases-main">
          <component v-if="description"
                     :is="description.structure + '-form'"
                     v-model="description"
                     ref="db_interface">
          </component>
        </bbn-pane>
        <!--div style="height: 20%">
          <code style="white-space: pre"
                v-html="descriptionJSON">
          </code>
        </div-->
      </bbn-splitter>
    </bbn-pane>
  </bbn-splitter>
</div>

<script type="text/x-template" id="tpl-bbn-databases-desc-host">
  <div class="bbn-margin bbn-content">
  </div>
</script>

<script type="text/x-template" id="tpl-bbn-databases-desc-database">
  <div class="bbn-margin bbn-content">

    <h3 class="bbn-c"><?= _("Informations") ?></h3>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Host") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b"
         v-text="value.host ? value.host : 'localhost'">
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Number of tables") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b"
         v-text="value.num_tables">
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Total size") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b"
         v-text="value.size">
    </div>

    <h3 class="bbn-c"><?= _("Operations") ?></h3>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-table"
                  title="<?= _("New table") ?>"
                  @click="create">
        <?= _("New table") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Create a new table") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-search"
                  title="<?= _("Search") ?>"
                  @click="search">
        <?= _("Search") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Search anything anywhere... This is quite a broad search, maybe you need to be more specific and use a table instead") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-chart_area"
                  title="<?= _("Analyze") ?>"
                  @click="analyze">
        <?= _("Analyze") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Checks the health of each table looking for errors in the records") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-arrow_circle_up"
                  title="<?= _("Import") ?>"
                  @click="importation">
        <?= _("Import") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Import from a file") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-arrow_circle_down"
                  title="<?= _("Export") ?>"
                  @click="exportation">
        <?= _("Export") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Export the database to a file") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-arrow_circle_right"
                  title="<?= _("Migrate") ?>"
                  @click="migration">
        <?= _("Migrate") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Copy your database on the same or another server") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-times"
                  title="<?= _("Delete") ?>"
                  @click="deletion">
        <?= _("Delete") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Deletes definitively the database") ?>
    </div>

    <h3 class="bbn-c"><?= _("bbn-ui") ?></h3>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding"
         v-if="value.in_db">
      <bbn-button icon="nf nf-fa-sync_alt"
                  title="<?= _("Update") ?>"
                  @click="update">
        <?= _("Update") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding"
         v-if="value.in_db">
      <?= _("You need to do this if the tables' structure has been changed outside of the application, in order to update the structure of your tables in the current database") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding"
         v-if="!value.in_db">
      <bbn-button icon="nf nf-fa-plus"
                  title="<?= _("Add") ?>"
                  @click="update">
        <?= _("Add") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding"
         v-if="!value.in_db">
      <?= _("Import your database's structure in the application if you want to use it inside it") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding"
         v-if="value.in_db">
      <bbn-button icon="nf nf-fa-minus"
                  title="<?= _("Remove") ?>"
                  @click="remove">
        <?= _("Remove") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding"
         v-if="value.in_db">
      <?= _("Delete all information stored about this database. it will not affect the database itself but all the objets and views you have created in this app.") ?>
    </div>

  </div>
</script>

<script type="text/x-template" id="tpl-bbn-databases-desc-table">
  <div class="bbn-margin bbn-content">

    <h3 class="bbn-c"><?= _("Information") ?></h3>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Host") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b" v-text="value.host ? value.host : 'localhost'"></div>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Database") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b" v-text="value.id.split('.')[0]"></div>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Engine") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b" v-text="value.engine"></div>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Collation") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b" v-text="value.collation"></div>

    <div class="bbn-block-left bbn-w-30 bbn-r" v-if="value.auto_increment">
      <?= _("Auto increment") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b" v-text="value.auto_increment" v-if="value.auto_increment"></div>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Number of columns") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b" v-text="value.num_columns"></div>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Number of rows") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b" v-text="value.num_rows"></div>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Data size") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b" v-text="value.data_size"></div>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Index size") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b" v-text="value.index_size"></div>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Total size") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b" v-text="value.size"></div>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Creation date") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b" v-text="creation"></div>

    <h3 class="bbn-c"><?= _("Operations") ?></h3>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-columns" title="<?= _("New column") ?>" @click="create">
        <?= _("New column") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Create a new column") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-key" title="<?= _("New key") ?>" @click="create">
        <?= _("New key") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Create a new key for this column") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-search" title="<?= _("Search") ?>" @click="search">
        <?= _("Search") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Perform a search with any criteria") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-area_chart" title="<?= _("Analyze") ?>" @click="analyze">
        <?= _("Analyze") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Checks the table's health and looks for errors in the records") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-arrow_circle_up" title="<?= _("Import") ?>" @click="importation">
        <?= _("Import Data") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Import from a file, JSON data, CSV or more") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-arrow_circle_down" title="<?= _("Export") ?>" @click="exportation">
        <?= _("Export") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Export the table to a file of many format") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-arrow_circle_right" title="<?= _("Migrate") ?>" @click="migration">
        <?= _("Migrate") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Copy your table on the same database or another server") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-times" title="<?= _("Delete") ?>" @click="deletion">
        <?= _("Delete") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Deletes definitively the table") ?>
    </div>

    <h3 class="bbn-c"><?= _("bbn-ui") ?></h3>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding"
         v-if="value.in_db">
      <bbn-button icon="nf nf-fa-sync_alt"
                  title="<?= _("Update") ?>"
                  @click="update">
        <?= _("Update") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding"
         v-if="value.in_db">
      <?= _("You need to do this if this table' structure has been changed outside of the application, in order to update the structure of your table in the current database") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding"
         v-if="!(value.in_db)">
      <bbn-button icon="nf nf-fa-plus"
                  title="<?= _("Add") ?>"
                  @click="update">
        <?= _("Add") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding"
         v-if="!value.in_db">
      <?= _("Import your table's structure in the application if you want to use it inside it") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding"
         v-if="value.in_db">
      <bbn-button icon="nf nf-fa-minus"
                  title="<?= _("Remove") ?>"
                  @click="remove">
        <?= _("Remove") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding"
         v-if="value.in_db">
      <?= _("Delete all information stored about this table. it will not affect the database itself but all the objets and views you have created in this app.") ?>
    </div>

  </div>
</script>

<script type="text/x-template" id="tpl-bbn-databases-desc-column">
  <div class="bbn-margin bbn-content">

    <h3 class="bbn-c"><?= _("Information") ?></h3>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Table") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b"
         v-text="value.id.split('.')[0] + '.' + value.id.split('.')[1]">
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Position") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b"
         v-text="value.position">
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Type") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b"
         v-html="value.type + (value.signed ? ' <em><?= _("signed") ?></em>' : '')">
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Nullable") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b"
         v-text="value.nullable ? '<?= _("Yes")?>' : '<?=_("No") ?>'">
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r"
         v-if="value.maxlength">
      <?= _("Max. Length") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b"
         v-text="value.maxlength"
         v-if="value.maxlength">
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding"
         v-if="value && (value.type === 'enum')">
      <?= _("Values") ?>
    </div>
    <ul class="bbn-block-right bbn-w-70 bbn-b bbn-vspadding"
        v-if="value && (value.type === 'enum')">
      <li v-for="v in value.values"
          v-text="v">
      </li>
    </ul>

    <div class="bbn-block-left bbn-w-30 bbn-r"
         v-if="value.default_value">
      <?= _("Default value") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b"
         v-if="value.default_value"
         v-text="value.default_value">
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Number of different values") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b">
      <bbn-button icon="nf nf-fa-sync_alt"
                  title="<?= _("Count") ?>"
                  @click="count_values()"
                  v-if="value.count">
        <?= _("Count") ?>
      </bbn-button>
      <span class="bbn-hmargin"
            v-text="num_values ? num_values : ''">

      </span>
      <span v-if="!value.count"><?= _("The table is empty") ?></span>
    </div>

    <h3 class="bbn-c"><?= _("Operations") ?></h3>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-search"
                  title="<?= _("Search") ?>"
                  @click="search">
        <?= _("Search") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Perform a search with any criteria") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-list"
                  title="<?= _("Values") ?>"
                  @click="importation">
        <?= _("Values") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Show each value of this column ordered by rows' length. You can then apply a filter") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-arrow_circle_down"
                  title="<?= _("Export") ?>"
                  @click="exportation">
        <?= _("Export") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Export the column to SQL format") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-arrow_circle_right"
                  title="<?= _("Migrate") ?>"
                  @click="migration">
        <?= _("Copy") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Copy the column to another table") ?>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <bbn-button icon="nf nf-fa-times"
                  title="<?= _("Delete") ?>"
                  @click="deletion">
        <?= _("Delete") ?>
      </bbn-button>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <?= _("Deletes definitively the column") ?>
    </div>

    <h3 class="bbn-c">app-ui</h3>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding"
         v-if="value.randomData">
      <?= _("Default display") ?> &nbsp;
      <i class="nf nf-fa-forward bbn-lg bbn-p"
         @click="nextRandom()"> </i>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding"
         v-if="value.randomData"
         v-html="value.randomData[currentRandom]">
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r bbn-vspadding">
      <?= _("Default editor") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-vspadding">
      <component :is="editor"></component>
    </div>

  </div>
</script>

<script type="text/x-template" id="tpl-bbn-databases-desc-key">
  <div class="bbn-margin bbn-content">

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <h3><?= _("Columns") ?></h3>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b">
      <h3 v-for="c in value.columns">{{c}}</h3>
    </div>

    <div class="bbn-block-left bbn-w-30 bbn-r">
      <?= _("Unique") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b"
         v-text="value.unique ? '<?= _("Yes")?>' : '<?=_("No") ?>'"
    ></div>

    <div class="bbn-block-left bbn-w-30 bbn-r"
         v-if="value.ref_column">
      <?= _("Relation") ?>
    </div>
    <div class="bbn-block-right bbn-w-70 bbn-b"
         v-if="value.ref_column"
         v-text="value.ref_table + '.' + value.ref_column + ' (' + value.ref_db + ')'">
    </div>

  </div>
</script>