<!-- HTML Document -->

<div class="bbn-padded">
  <div class="bbn-grid-fields">
    <div style="width: 6em">
      <?=_("Name")?>
    </div>
    <bbn-editable v-model="table.currentData.table"
                  @save="rename"
                  :required="true"/>

    <div>
      <?=_("Comment")?>
    </div>
    <bbn-editable v-model="table.currentData.comment"
                  @save="saveComment"
                  :required="true"/>

    <div>
      <?=_("Size")?>:
    </div>
    <div v-text="table.currentData.size"/>

    <div v-if="table.currentData.is_real">
      #<?=_("Records")?>:
    </div>
    <div v-text="format(table.currentData.count)"
         v-if="table.currentData.is_real"/>
  </div>
</div>
