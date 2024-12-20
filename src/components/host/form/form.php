<!-- HTML Document -->
<bbn-form :source="formData"
          ref="form"
          :validation="() => checked === 1"
          :action="root + '/actions/host/add'"
          @success="success"
          confirm-leave="<?= _("Are you sure you want to leave this form without saving your changes?") ?>">
  <div class="bbn-grid-fields bbn-padding">
    <div><?= _('Host') ?></div>
    <bbn-input bbn-model="formData.host"
               name="host"
               :required="true"
               @change="changeHost"
    />

    <div><?= _('Name') ?></div>
    <bbn-input bbn-model="formData.name"
               name="name"
               :required="true"/>

    <fieldset class="bbn-grid-full" bbn-if="source.engine !== 'sqlite'">
      <legend><?= _('Administrator') ?></legend>
      <div class="bbn-grid-fields">
        <span><?= _('Username') ?></span>
        <bbn-input bbn-model="formData.username"
               		 name="username"
                   :required="true"
                   @change="checkConnection"/>

        <span><?= _('Password') ?></span>
        <bbn-input bbn-model="formData.password"
               		 name="password"
                   :required="true"
                   type="password"
                   @change="checkConnection"/>
      </div>
    </fieldset>
  </div>
</bbn-form>
