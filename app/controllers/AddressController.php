<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Address.php";

class AddressController extends Controller
{
    private $addressModel;

    public function __construct($pdo)
    {
        $this->addressModel = new Address($pdo);
    }

    public function index()
    {
        requireLogin();

        $addresses = $this->addressModel->getAllByUserId($_SESSION["user_id"]);

        $this->render("client/addresses/index", [
            "addresses" => $addresses,
            "message" => $_GET["message"] ?? ""
        ]);
    }

    public function create()
    {
        requireLogin();

        $this->render("client/addresses/create", [
            "message" => ""
        ]);
    }

    public function store()
    {
        requireLogin();

        $data = $this->getFormData();

        if ($data === null) {
            $this->render("client/addresses/create", [
                "message" => "Vui lòng nhập đầy đủ thông tin bắt buộc"
            ]);
            return;
        }

        $this->addressModel->create($_SESSION["user_id"], $data);

        $this->redirect("/addresses");
    }

    public function edit($id)
    {
        requireLogin();

        $address = $this->getOwnedAddressOrDie($id);

        $this->render("client/addresses/edit", [
            "address" => $address,
            "message" => ""
        ]);
    }

    public function update($id)
    {
        requireLogin();

        $address = $this->getOwnedAddressOrDie($id);

        $data = $this->getFormData();

        if ($data === null) {
            $this->render("client/addresses/edit", [
                "address" => $address,
                "message" => "Vui lòng nhập đầy đủ thông tin bắt buộc"
            ]);
            return;
        }

        $this->addressModel->update($id, $_SESSION["user_id"], $data);

        $this->redirect("/addresses");
    }

    public function delete()
    {
        requireLogin();

        $id = (int) ($_POST["address_id"] ?? 0);

        $address = $this->addressModel->getById($id);

        if (!$address || (int) $address["user_id"] !== (int) $_SESSION["user_id"]) {
            $this->json(["success" => false, "message" => "Không tìm thấy địa chỉ"], 404);
        }

        $this->addressModel->delete($id, $_SESSION["user_id"]);

        $this->json([
            "success" => true,
            "message" => "Đã xóa địa chỉ"
        ]);
    }

    public function setDefault()
    {
        requireLogin();

        $id = (int) ($_POST["address_id"] ?? 0);

        $address = $this->addressModel->getById($id);

        if (!$address || (int) $address["user_id"] !== (int) $_SESSION["user_id"]) {
            $this->json(["success" => false, "message" => "Không tìm thấy địa chỉ"], 404);
        }

        $this->addressModel->setDefault($id, $_SESSION["user_id"]);

        $this->json([
            "success" => true,
            "message" => "Đã đặt làm địa chỉ mặc định"
        ]);
    }

    private function getFormData()
    {
        $receiverName = trim($_POST["receiver_name"] ?? "");
        $phone = trim($_POST["phone"] ?? "");
        $addressDetail = trim($_POST["address_detail"] ?? "");

        if ($receiverName === "" || $phone === "" || $addressDetail === "") {
            return null;
        }

        return [
            "receiver_name" => $receiverName,
            "phone" => $phone,
            "address_detail" => $addressDetail,
            "ward" => trim($_POST["ward"] ?? ""),
            "district" => trim($_POST["district"] ?? ""),
            "province" => trim($_POST["province"] ?? ""),
            "is_default" => isset($_POST["is_default"])
        ];
    }

    private function getOwnedAddressOrDie($id)
    {
        $address = $this->addressModel->getById($id);

        if (!$address || (int) $address["user_id"] !== (int) $_SESSION["user_id"]) {
            die("Không tìm thấy địa chỉ");
        }

        return $address;
    }
}
