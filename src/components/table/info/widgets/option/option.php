<!-- HTML Document -->

<div class="bbn-padding">
  <div class="bbn-state-error bbn-padding bbn-c"
       v-if="!table.currentData.table_id">
    <?= _("No option") ?>
  </div>
  <div class="bbn-grid-fields"
       v-else>
    <div style="width: 6em">
      <?= _("ID") ?>
    </div>
    <div v-text="table.currentData.table_id"/>

    <div>
      <?= _("Title") ?>
    </div>
    <bbn-editable v-model="table.currentData.option.text"
                  @save="saveTitle"
                  :required="true"/>

    <div>
      <?= _("Item viewer") ?>
      <bbn-tooltip source="<?= _("A component which will be used to show an item from this table in other lists or widgets") ?>"/>
    </div>
    <div>
      <bbn-button icon="nf nf-custom-folder_open"
                  :text="_('Browse Item Viewers')"
                  @click="browseItemViewer"/>

    <bbn-editable v-model="table.currentData.option.itemComponent"
                  @save="saveItemComponent"/>
    </div>


    <div>
      <?= _("Row editor") ?>
      <bbn-tooltip source="<?= _("A component which will be used to edit a whole item from this table") ?>"/>
    </div>
    <div>
      <bbn-button icon="nf nf-custom-folder_open"
                  :text="_('Browse Row Editors')"
                  @click="browseRowEditor"/>
    </div>

    <div>
      <?= _("Display columns") ?>
      <bbn-tooltip source="<?= _("Pick the column(s) that you want to see from this table in other lists") ?>"/>
    </div>
    <div style="height: 10em">
      <div class="bbn-80">
        <bbn-editable v-model="table.currentData.option.dcolumns"
                      component="bbn-multiselect"
                      :componentOptions="{source: columns}"
                      @save="saveDisplayColumns"
                      :required="true"
                      class="bbn-80"/>
      </div>
    </div>
  </div>
</div>
