<?php


namespace app\modules\admin\models;


use app\models\Image;
use yii\base\Model;

/**
 * Class ImageModel
 * @package app\modules\admin\models
 */
class ImageModel extends Model
{
    public $id;
    public $image;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['image'], 'required'],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * @return bool
     */
    public function upload()
    {

        if ($this->validate()) {
            $fileName = $this->generationFileName();
            $this->image->saveAs($this->getFolder() . $fileName);

            return true;
        }


    }

    /**
     * @return \Exception
     */
    public function save()
    {
        $model = new Image();
        $model->image = $this->image->name;
        if (!$model->save(false)) {
            return new \Exception('Failed to save image');
        }
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete()
    {
        $model = Image::findOne($this->id);
        $this->deleteImg();
        $model->delete();
    }


    /**
     * @param int $id
     * @return ImageModel
     */
    static function findOne(int $id)
    {
        if (($model = Image::findOne($id)) !== null) {
            $imageModel = new ImageModel();
            $imageModel->id = $model->id;
            $imageModel->image = $model->image;
            return $imageModel;
        }
    }

    /**
     * @return string
     */
    private function getFolder()
    {
        return 'uploads/';
    }

    /**
     * @return string
     */
    private function generationFileName()
    {
        return $this->image->baseName . '.' . $this->image->extension;

    }

    private function deleteImg()
    {

        if (!empty($this->image) && $this->image != null) {
            if (file_exists($this->getFolder() . $this->image)) {
                unlink($this->getFolder() . $this->image);
            }
        }
    }

}