начать заполнять таблицу товаров
использовать уже сделанную таблицу брендов
если при добавлении нового товара такого бренда еще нет, то добавлять по ходу в таблицу брендов

обновить версию PHP, прописать типы результатам функций, аргументам функций, пропертям классов
писать зеленую доку для шторма перед каждым fetch

если с картинкой ошибки, все равно запись в базу добавляется (на бонлибро)?
при выводе на карточку товара обрамлять все данные из базы в htmlspecialchars
а textarea также снаружи в nl2br(чтобы переносы строк в textarea перевести в br)
на бонлибро book-form.php надо переложить в папку include?
на бонлибро все связи по foreign keys заменить с cascade на restrict, предварительно сделав дамп базы.
потом попробовать удалять участвующие в связях столбцы в админке и посмотреть, что получится

- в updateCategory $statement->bindValue(':id', $this->id, PDO::PARAM_INT); - или параметр STRING?
    аналогично в Product
- в setProductImage в строке 230, наверно, не нужна проверка на null, потому что пустой destination никогда не придет
    если нет, то изменить, соответственно, в методе setCategoryImage
- кусок кода в валидации картинки дублируется в Product и Category

- поменять класс в модалке добавления размера с add-brand-modal-submit на нормальный
- и то же самое при редактировании (?)
