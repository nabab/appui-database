<!-- HTML Document -->

<bbn-form :action="root + 'actions/table/option'"
          :source="source.option">
  <div class="bbn-grid-fields bbn-padding">
    <label>
      <?= _("Title") ?>
    </label>
    <bbn-input bbn-model="source.option.text"
               @save="saveTitle"/>

    <label>
      <?= _("Column viewer") ?>
      <bbn-tooltip source="<?= _("A component which will be used to show this column in other lists or widgets") ?>"/>
    </label>
    <div>
      <span bbn-if="source.option.viewer"
            bbn-html="source.option.viewer + '<br>'"/>
      <bbn-button icon="nf nf-fa-edit"
                  :label="source.option.viewer ? _('Change') : _('Set')"
                  @click="browseColumnViewer"/>
      <bbn-button bbn-if="source.option.viewer"
                  icon="nf nf-fa-trash"
                  :label="_('Unset')"
                  @click="source.option.viewer = null"/>
    </div>

    <label>
      <?= _("Column editor") ?>
      <bbn-tooltip source="<?= _("A component which will be used to edit this column") ?>"/>
    </label>
    <div>
      <span bbn-if="source.option.editor"
            bbn-html="source.option.editor + '<br>'"/>
      <bbn-button icon="nf nf-fa-edit"
                  :label="source.option.editor ? _('Change') : _('Set')"
                  @click="browseColumnEditor"/>
      <bbn-button bbn-if="source.option.editor"
                  icon="nf nf-fa-trash"
                  :label="_('Unset')"
                  @click="source.option.editor = null"/>
    </div>
  </div>
</bbn-form>