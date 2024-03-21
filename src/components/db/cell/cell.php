<!-- HTML Document -->
<div>
  <div class="bbn-w-100 bbn-nowrap">
    <a class="bbn-b bbn-m"
       :href="link"
       v-text="source.name"/>
    &nbsp;|&nbsp;
    <span v-if="source.is_real"
          class="bbn-success-text">
      <?= _("Exists in the host") ?>
    </span>
    <span v-else
          class="bbn-error-text">
      <?= _("Doesn't exist in the host") ?>
    </span>
    &nbsp;|&nbsp;
    <span v-if="source.is_virtual"
          class="bbn-success-text">
      <?= _("Exists as options") ?>
    </span>
    <span v-else
          class="bbn-error-text">
      <?= _("Doesn't exist as options") ?>
    </span>
    <br>
    <span v-if="source.last_check">
      <?= _("Info date") ?>
      <span v-text="fdate(source.last_check)"/>
    </span>
    <span v-else>
      <?= _("The info has never been built") ?>
    </span>
  </div>
</div>