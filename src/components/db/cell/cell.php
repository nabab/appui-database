<!-- HTML Document -->
<div>
  <div class="bbn-w-100 bbn-nowrap">
    <a class="bbn-b bbn-m"
       :href="link"
       bbn-text="source.name"/>
    &nbsp;|&nbsp;
    <span bbn-if="source.is_real"
          class="bbn-success-text">
      <?= _("Exists in the host") ?>
    </span>
    <span bbn-else
          class="bbn-error-text">
      <?= _("Doesn't exist in the host") ?>
    </span>
    &nbsp;|&nbsp;
    <span bbn-if="source.is_virtual"
          class="bbn-success-text">
      <?= _("Exists as options") ?>
    </span>
    <span bbn-else
          class="bbn-error-text">
      <?= _("Doesn't exist as options") ?>
    </span>
    <br>
    <span bbn-if="source.last_check">
      <?= _("Info date") ?>
      <span bbn-text="fdate(source.last_check)"/>
    </span>
    <span bbn-else>
      <?= _("The info has never been built") ?>
    </span>
  </div>
</div>