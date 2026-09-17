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

    // GET /addresses - danh sách địa chỉ của customer đang đăng nhập
    public function index()
    {
        requireLogin();

        $addresses = $this->addressModel->getAllByUserId($_SESSION["user_id"]);

        $this->render("client/addresses/index", [
            "addresses" => $addresses,
            "message" => $_GET["message"] ?? ""
        ]);
    }

    // GET /addresses/create - form thêm địa chỉ
    public function create()
    {
        requireLogin();

        $this->render("client/addresses/create", [
            "message" => ""
        ]);
    }

    // POST /addresses/create - xử lý thêm địa chỉ
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

    // GET /addresses/edit/{id} - form sửa địa chỉ
    public function edit($id)
    {
        requireLogin();

        $address = $this->getOwnedAddressOrDie($id);

        $this->render("client/addresses/edit", [
            "address" => $address,
            "message" => ""
        ]);
    }

    // POST /addresses/edit/{id} - xử lý cập nhật địa chỉ
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

    // POST /addresses/delete - JS gọi AJAX khi bấm nút "Xóa"
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

    // POST /addresses/set-default - JS gọi AJAX khi bấm "Đặt làm mặc định"
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

    // Lấy + validate dữ liệu form (dùng chung cho store/update). Trả null nếu thiếu trường bắt buộc.
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

    // Lấy địa chỉ theo ID và kiểm tra thuộc đúng user đang đăng nhập, nếu không thì dừng luôn
    private function getOwnedAddressOrDie($id)
    {
        $address = $this->addressModel->getById($id);

        if (!$address || (int) $address["user_id"] !== (int) $_SESSION["user_id"]) {
            die("Không tìm thấy địa chỉ");
        }

        return $address;
    }
}
