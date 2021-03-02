<div class="form-group row">
    <label for="ordernum" class="col-sm-2 col-form-label text-right">Вес</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="ordernum" name="ordernum" aria-describedby="ordernumHelp"
               placeholder="(для сортировки)" value="<?= $this->moduleData->material->params['ordernum'] ?>">
    </div>
    <label for="tags" class="col-sm-2 col-form-label text-right">Тэги</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="tags" name="tags" aria-describedby="tagsHelp" placeholder="Тэги"
               value="<?= $this->moduleData->material->params['tags'] ?>">
    </div>
</div>