<div class="bbn-overlay">
  <bbn-table :source="tableSource"
             uid="name"
             :scrollable="true"
             button-mode="menu"
             editor="appui-database-column-editor"
             editable="popup"
             :editor-options="editorOptions"
             ref="table"
             :tr-class="trClass"
             :selection="true"
             @toggle="onTableToggle">
    <bbns-column :buttons="getButtons"
                 :width="40"
                 cls="bbn-c"/>
    <bbns-column bbn-if="hasPosition"
                 field="position"
                 label="<i class='nf nf-md-numeric bbn-lg'></i>"
                 flabel="<?=_("Position in the table")?>"
                 :width="40"
                 cls="bbn-c"/>
    <bbns-column field="key"
                 label="<i class='nf nf-fa-key bbn-m'></i>"
                 flabel="<?=_("Are there keys on the column?")?>"
                 :width="40"
                 cls="bbn-c"
                 :render="renderKey"/>
    <bbns-column field="name"
                 label="<?=_("Name")?>"
                 :min-width="200"
                 :render="renderName"/>
    <bbns-column field="type"
                 label="<?=_("Type")?>"
                 :width="100"
                 cls="bbn-c"
                 :render="renderType"/>
    <bbns-column field="signed"
                 label="<i class='nf nf-md-plus_minus_variant'></i>"
                 flabel="<?=_("Unsigned")?>"
                 :width="40"
                 cls="bbn-c"
                 :render="renderSigned"/>
    <bbns-column field="null"
                 label="Null"
                 :width="70"
                 cls="bbn-c"
                 :render="renderNull"/>
    <bbns-column field="default"
                 label="<?=_("Default")?>"
                 :width="80"
                 cls="bbn-c"/>
    <bbns-column field="charset"
                 label="<?=_("Charset")?>"
                 :width="100"
                 cls="bbn-c"/>
    <bbns-column field="collation"
                 label="<?=_("Collation")?>"
                 :width="150"
                 cls="bbn-c"/>
    <bbns-column field="is_real"
                  label="<i class='nf nf-cod-database'></i>"
                  full-label="<?= _("Exists in the host") ?>"
                  :render="renderRealVirtual"
                  :width="30"
                  cls="bbn-c"/>
    <bbns-column field="is_virtual"
                  label="<i class='nf nf-md-opera'></i>"
                  full-label="<?= _("Exists as options") ?>"
                  :render="renderRealVirtual"
                  :width="30"
                  cls="bbn-c"/>
    <bbns-column field="option.viewer"
                 label="<?=_("Viewer")?>"
                 :width="100"
                 cls="bbn-c"/>
    <bbns-column field="option.editor"
                 label="<?=_("Editor")?>"
                 :width="100"
                 cls="bbn-c"/>
  </bbn-table>
</div>
