<!-- HTML Document -->

<div class="bbn-padded">
  <div class="bbn-state-error bbn-padded bbn-c"
       v-if="!table.source.table_id">
    <?=_("No option")?>
  </div>
  <div class="bbn-grid-fields"
       v-else>
    <div style="width: 6em">
      <?=_("ID")?>
    </div>
    <div v-text="table.source.table_id"/>

    <div>
      <?=_("Title")?>
    </div>
    <bbn-editable v-model="table.source.option.text"
                  @save="saveTitle"
                  :required="true"/>

    <div>
      <?=_("Item viewer")?> 
      <bbn-tooltip source="<?=_("A component which will be used to show an item from this table in other lists or widgets")?>"/>
    </div>
    <div>
      <bbn-button :notext="true"
                  icon="nf nf-custom-folder_open"
                  text="_(Browse)"
                  @click="browse"/>

    <bbn-editable v-model="table.source.option.itemComponent"
                  @save="saveItemComponent"/>
    </div>


    <div>
      <?=_("Row editor")?> 
      <bbn-tooltip source="<?=_("A component which will be used to edit a whole item from this table")?>"/>
    </div>
    <bbn-editable v-model="table.source.option.editor"
                  @edit="editEditor"
                  @save="saveEditor"/>

    <div>
      <?=_("Display columns")?>
      <bbn-tooltip source="<?=_("Pick the column(s) that you want to see from this table in other lists")?>"/>
    </div>
    <div style="height: 10em">
      <div class="bbn-100">
        <bbn-editable v-model="table.source.option.dcolumns"
                      component="bbn-multiselect"
                      :componentOptions="{source: columns}"
                      @save="saveDisplayColumns"
                      :required="true"/>
      </div>
    </div>
  </div>
</div>
