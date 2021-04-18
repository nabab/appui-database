<!-- HTML Document -->

<div class="bbn-padded">
  <div class="bbn-grid-fields">
    <div style="width: 6em">
      <?=_("Name")?>
    </div>
    <div v-text="table.source.table"/>

    <div>
      <?=_("Comment")?>
    </div>
    <bbn-editable v-model="table.source.comment"
                  @save="saveComment"
                  :required="true"/>

    <div>
      <?=_("Size")?>:
    </div>
    <div v-text="table.source.size"/>

    <div v-if="table.source.is_real">
      #<?=_("Records")?>:
    </div>
    <div v-text="format(table.source.count)"
         v-if="table.source.is_real"/>
  </div>
</div>
