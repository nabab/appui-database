(() => {
  return {
    data(){
      return {
        root: appui.databases.source.root
      };
      /*let cols = [];
      if ( this.source.columns.length ){
        bbn.fn.each(this.source.columns, (c) => {
          if ( cols.length > 5 ){
            c.hidden = true;
          }
          cols.push(c);
        })
      }
      return {
        //columns: cols;
      }*/

    },
    computed: {
      columns(){
        let r = [];
        let i = 0;
        bbn.fn.iterate(this.source.structure.fields, (a, n) => {
          i++;
          let o = {
            title: n,
            field: n,
            options: {field: n},
            width: 100
          };
          if (a.type === 'binary') {
            //o.options.type = 'string';
            o.filterable = true;
          	o.width = this.source.constraints[n] ? 80 : 40;
            o.cls = 'bbn-c';
            o.buttons = row => {
              let btns = [{
                text: n + ' - ' + row[n],
                notext: true,
                icon: 'nf nf-mdi-content_copy',
                title: bbn._('Copy uid'),
                action: this.copy
              }];
              if (this.source.constraints[n]) {
                btns.push({
                  notext: true,
                  icon: 'nf nf-fa-eye',
                  text: bbn._('See referenced row'),
                  action: this.see
                });
              }
              return btns;
            };
          }
          else if (a.type === 'int') {
            o.options.type = 'numeric';
            o.width = 10 * (a.maxlength || 4);
          }
          else if (a.type === 'varchar') {
            o.options.type = 'string';
            o.width = 30 + (5 * ((a.maxlength || 10) > 50 ? 50 : (a.maxlength || 10)));
          }
          else if (a.type === 'date') {
            o.options.type = 'date';
            o.width = 80;
          }
          else if (a.type === 'datetime') {
            o.options.type = 'datetime';
            o.width = 120;
          }

          r.push(o);
          /*
          if ( i > 5 ){
            o.hidden = true;
          }
          */
        });

        r.push({
          title: bbn._('Actions'),
          fixed: 'right',
          buttons: [{
            notext: true,
            icon: 'nf nf-fa-edit',
            action: this.edit
          }],
          width: '60'
        })
        return r;
      }
    },
    methods:{
      see(row, col){
        if (this.source.constraints[col.field] && row[col.field]) {
          this.post(
            this.root + 'actions/show',
            bbn.fn.extend({id: row[col.field]}, this.source.constraints[col.field]),
            () => {
              appui.success("JJJJJJ");
            }
          )
        }
      },
      edit(row, obj, idx){
        this.$refs.table.edit(bbn.fn.extend(row, {original: this.$refs.table.originalData[idx]}), bbn._('Edit data'), idx);
      },
      success(d){
        if (d.success) {
          appui.success(bbn._('Data successfully updated'))
        }
      },
      copy(row, col, idx){
        if (row[col.field]) {
          this.$refs.copyUid.value = row[col.field];
          this.$refs.copyUid.select();
          document.execCommand('copy');
          this.$nextTick(() =>{
            appui.notify(false, {content:'Uid copied'}, 3);
          });
        }
        else {
          appui.error(bbn._('The field has value ' + (row[col.field] !== '') ? row[col.field] : '""'))
        }
      },
    }
  }
})();
