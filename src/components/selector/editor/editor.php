<!-- HTML Document -->

<div class="appui-database-roweditorselector bbn-iblock">
  <div bbn-if="ready" class="bbn-padding">
    <h3 class="bbn-c"><?= _("Row editor selector") ?></h3>
    <p>
      <?= _("Select the component that will be used to edit a row") ?>
    </p>
    <div class="bbn-grid-fields">
      <label><?= _("Root") ?></label>
      <bbn-dropdown :source="project.path"
                    bbn-if="ready"
                    source-value="id"
                    class="bbn-wide"
                    bbn-model="currentPathId"
                    :disabled="false"/>

      <div class="bbn-label"> </div>
      <div>
        <bbn-tree :source="root + 'data/tree'"
                  :data="dataTree"
                  ref="tree"
                  @select="select"
                  :scrollable="false"/>
      </div>
    </div>
  </div>
  <bbn-loader bbn-else/>
</div>