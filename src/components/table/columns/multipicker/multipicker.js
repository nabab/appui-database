(() => {
  return {
    data() {
      return {
        formData: {
          id: this.source.option?.id,
          dcolumns: this.source.option?.dcolumns || [],
        }
      }
    },
    computed: {
      fields() {
        const ar = [];
        bbn.fn.iterate(this.source.structure.fields, (f, name) => {
          ar.push(bbn.fn.extend({name}, f));
        });

        return bbn.fn.order(ar, 'position');
      },
      textValues() {
        return this.fields.map(f => {
          return {text: f.name, value: f.name};
        })
      }
    }
  };
})();
