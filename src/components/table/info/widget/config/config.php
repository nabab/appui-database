<!-- HTML Document -->

<div class="bbn-padding">
  <div class="bbn-grid-fields" bbn-if="source.table">
    <div class="bbn-label bbn-lg">
      
    </div>
    <bbn-editable class="bbn-lg bbn-b"
                  bbn-model="source.table"
                  @save="rename"
                  :required="true"/>

    <div>
      <?= _("Size") ?>:
    </div>
    <div bbn-text="size"/>

    <div bbn-if="source.is_real">
      #<?= _("Records") ?>:
    </div>
    <div bbn-text="format(source.count)"
         bbn-if="source.is_real"/>

    <div>
      <?= _("Comment") ?>
    </div>
    <bbn-editable bbn-model="source.comment"
                  @save="saveComment"
                  :required="true"/>

  </div>
</div>
